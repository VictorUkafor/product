<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Uploads;

class AdminBlogPostCreate extends Controller {

    public function index() {

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['url'] = get_slug($_POST['url']);
            $_POST['title'] = Database::clean_string($_POST['title']);
            $_POST['description'] = Database::clean_string($_POST['description']);
            $_POST['editor'] = in_array($_POST['editor'], ['wysiwyg', 'raw']) ? Database::clean_string($_POST['editor']) : 'raw';
            $_POST['blog_posts_category_id'] = empty($_POST['blog_posts_category_id']) ? null : (int) $_POST['blog_posts_category_id'];
            $_POST['language'] = !empty($_POST['language']) ? Database::clean_string($_POST['language']) : null;
            $image = !empty($_FILES['image']['name']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            $required_fields = ['title', 'url'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(db()->where('url', $_POST['url'])->where('language', $_POST['language'])->has('blog_posts')) {
                Alerts::add_field_error('url', l('admin_blog.error_message.url_exists'));
            }

            if($image) {
                $file_name = $_FILES['image']['name'];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES['image']['tmp_name'];

                if($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), get_max_upload()));
                }

                if($_FILES['image']['error'] && $_FILES['image']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('blog'))) {
                    Alerts::add_error(l('global.error_message.invalid_file_type'));
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(UPLOADS_PATH . 'blog/')) {
                        Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . 'blog/'));
                    }
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/main/' . $image_new_name,
                                'ContentType' => mime_content_type($file_temp),
                                'SourceFile' => $file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            Alerts::add_error($exception->getMessage());
                        }
                    }

                    /* Local uploading */
                    else {
                        /* Upload the original */
                        move_uploaded_file($file_temp, UPLOADS_PATH . 'blog/' . $image_new_name);
                    }

                }
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('blog_posts', [
                    'blog_posts_category_id' => $_POST['blog_posts_category_id'],
                    'url' => $_POST['url'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'image' => $image ? $image_new_name : null,
                    'editor' => $_POST['editor'],
                    'content' => $_POST['content'],
                    'language' => $_POST['language'],
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . filter_var($_POST['title'], FILTER_SANITIZE_STRING) . '</strong>'));

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('blog_posts');

                redirect('admin/blog-posts');
            }

        }

        /* Get the blog posts categories available */
        $blog_posts_categories = db()->get('blog_posts_categories', null, ['blog_posts_category_id', 'title']);

        /* Set default values */
        $values = [
            'blog_posts_category_id' => $_POST['blog_posts_category_id'] ?? '',
            'title' => $_POST['title'] ?? '',
            'url' => $_POST['url'] ?? '',
            'description' => $_POST['description'] ?? '',
            'editor' => $_POST['editor'] ?? 'wysiwyg',
            'content' => $_POST['content'] ?? '',
            'language' => $_POST['language'] ?? '',
        ];

        $data = [
            'values' => $values,
            'blog_posts_categories' => $blog_posts_categories
        ];

        /* Main View */
        $view = new \Altum\Views\View('admin/blog-post-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

}
