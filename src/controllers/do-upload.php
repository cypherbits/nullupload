<?php

$app->post('/', function ($request, $response, $args) {

set_time_limit(851);

    $settings = $this->get('settings')['nullupload'];

    $totalsize = (int) file_get_contents(__DIR__ . "/../../usedSpace");

    if ((int) $settings['maxLimit'] > $totalsize) {

        if (!empty($_FILES['inputFile'])) {

            $upload = Upload::factory($settings['uploadPath'], $settings['root']);
            $upload->file($_FILES['inputFile']);

            $upload->set_max_file_size($settings['maxFileSize']);

            $upload->set_allowed_mime_types($settings['mimesForb']);

            if ($request->getParam('checkChangeFilename') == 'yes') {

                class validation {

                    public function check_name_length($object) {

                        if (mb_strlen($object->file['original_filename']) > 240) {

                            $object->set_error('File name is too long.');
                        }
                    }

                }

                $validation = new validation();

                $upload->callbacks($validation, array('check_name_length'));
            }


            $results = $upload->upload();


            $errors = $upload->get_errors();

            if (count($errors) > 0) {

                return $this->view->render($response, 'upload-error.html', [
                            'page' => 'do-upload',
                            'errormsg' => $errors[0]
                ]);
            } else {

                $id = IOHelper::getRandomName(5);
                $origname = $results['original_filename'];
                $filename = $results['filename'];
                $_array = explode('.', $origname);
                if (count($_array) > 1) {
                    $extension = end($_array);
                } else {
                    $extension = "";
                }

                $file = new Files();
                $file->setId($id);
                if ($request->getParam('checkChangeFilename') == 'yes') {
                    $file->setOrigname(null);
                    $file->setExtension($extension);
                } else {
                    $file->setOrigname($origname);
                    $file->setExtension("none");
                }
                $file->setFilename($filename);
                $file->setUploaddate("now");
                $file->setType(substr($results['mime'], 0, 64));
                if ($request->getParam('checkWithPass') == 'yes') {
                    $password = IOHelper::getRandomName(7);
                    $hpassword = hash("sha256", $password);
                    $file->setPassword($hpassword);

                    $downloadUrlWithPass = 'http://' . $_SERVER['SERVER_NAME'] . $this->router->pathFor("download", [
                                'id' => $id,
                                'password' => $password
                    ]);
                } else {
                    $downloadUrlWithPass = null;
                }
                $password = isset($password) ? $password : 'No password';

                $deletePassword = IOHelper::getRandomName(7);

                $file->setDeletepassword(hash("sha256", $deletePassword));

                $selectDelete = $request->getParam('selectDelete');

                switch ($selectDelete) {
                    case "24h":
                        $deleteDate = strtotime('+1 days', time());
                        break;
                    case "7d":
                        $deleteDate = strtotime('+7 days', time());
                        break;
                    case "15d":
                        $deleteDate = strtotime('+15 days', time());
                        break;
                    case "1m":
                        $deleteDate = strtotime('+1 month', time());
                        break;
                    case "3m":
                        $deleteDate = strtotime('+3 month', time());
                        break;
                    case "6m":
                        $deleteDate = strtotime('+6 month', time());
                        break;
                    case "1y":
                        $deleteDate = strtotime('+1 year', time());
                        break;
                    default:
                        $deleteDate = strtotime('+1 days', time());
                }

                $file->setDeletedate($deleteDate);

                $fileHash = sha1_file(__DIR__ . '/../' . '../uploads/' . $filename);

                $file->setIntegrity($fileHash);

                $file->save();

                //$downloadUrl = "https://" . $_SERVER['SERVER_NAME'] . "/download-" . $id;
                $downloadUrl = 'http://' . $_SERVER['SERVER_NAME'] . $this->router->pathFor("download", [
                            'id' => $id
                ]);

                //$deleteUrl = "https://" . $_SERVER['SERVER_NAME'] . "/delete-" . $id . "-" . $deletePassword;
                $deleteUrl = 'http://' . $_SERVER['SERVER_NAME'] . $this->router->pathFor("delete", [
                            'id' => $id,
                            'deletePassword' => $deletePassword
                ]);

                $totalsize = IOHelper::get_total_size(__DIR__ . "/../../uploads");
                file_put_contents(__DIR__ . "/../../usedSpace", $totalsize);


                $deleteDate = IOHelper::getStringCountdownDelete($deleteDate);

                return $this->view->render($response, 'upload.html', [
                            'page' => 'do-upload',
                            'downloadUrl' => $downloadUrl,
                            'password' => $password,
                            'deleteDate' => $deleteDate,
                            'deleteUrl' => $deleteUrl,
                            'downloadUrlWithPass' => $downloadUrlWithPass
                ]);
            }

            return $response->withRedirect('./');
        } else {
            return $response->withRedirect('./');
        }
    } else {
        return $this->view->render($response, 'upload-error.html', [
                    'page' => 'do-upload',
                    'errormsg' => 'Server is full'
        ]);
    }
});
