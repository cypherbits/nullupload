<?php

use nullupload\DB;

$app->post('/', function ($request, $response, $args) {

set_time_limit(851);

    $settings = $this->get('settings')['nullupload'];

    $totalsize = (int) DB::getConfig(DB::$histoTotalFileSize);

    if ((int) $settings['maxLimit'] > $totalsize) {

        if (!empty($_FILES['inputFile'])) {

            $upload = Upload::factory($settings['uploadPath'], $settings['root']);
            $upload->file($_FILES['inputFile']);

            $upload->set_max_file_size($settings['maxFileSize']);

            //This is actually for setting the forbidden mime types, not the allowed...
            $upload->set_allowed_mime_types($settings['mimesForb'], $settings['mimesForbContains']);

            if ($request->getParam('checkChangeFilename') === 'yes') {

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
                    $extension = strtolower(end($_array));
                    $extension = strlen($extension) > 10 ? substr($extension, 0, 10): $extension;
                } else {
                    $extension = "";
                }

                if ($request->getParam('checkChangeFilename') === 'yes') {
                    $origname = null;
                } else{
                    $extension = "none";
                }

                if ($request->getParam('checkWithPass') === 'yes') {
                    $password = IOHelper::getRandomName(7);
                    $hpassword = hash("sha256", $password);

                    $downloadUrlWithPass = IOHelper::getProto() . $_SERVER['SERVER_NAME'] . $this->router->pathFor("download", [
                                'id' => $id,
                                'password' => $password
                    ]);
                } else {
                    $downloadUrlWithPass = null;
                    $hpassword = null;
                }

                $password = $password ?? 'No password';

                $deletePassword = IOHelper::getRandomName(7);

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

                $fileHash = hash_file("sha256", __DIR__ . '/../' . '../uploads/' . $filename);

                $stm = DB::getDB()->prepare("select count(*) as nhash from bannedFiles where fileHash = :fileHash limit 1");
                $stm->bindValue(":fileHash",$fileHash, PDO::PARAM_STR);
                $stm->execute();

                $nhash = (int) $stm->fetch()['nhash'];

                if ($nhash > 0){
                    return $this->view->render($response, 'download-error.html', [
                        'page' => 'download',
                        'errormsg' => 'This file is banned, you cannot upload it.'
                    ]);
                }

                //If we want to upload a file already uploaded check the number of downloads: if 0 user may have lost the link then delete the older file and allow upload, if more than 0 block upload
                $stm = DB::getDB()->prepare("select id, nDownloads, filename from files where integrity = :fintegrity limit 1");
                $stm->bindValue(":fintegrity",$fileHash, PDO::PARAM_STR);
                $stm->execute();

                if ($stm->rowCount() > 0){
                    $eFile = $stm->fetch();

                    if ($eFile['nDownloads'] === 0){
                        $path = __DIR__ . "/../../uploads/";

                        IOHelper::delete($eFile, $path);

                    }else{
                        return $this->view->render($response, 'download-error.html', [
                            'page' => 'download',
                            'errormsg' => 'This file is already uploaded by someone else, sorry.'
                        ]);
                    }
                }

                $fileSize = filesize(__DIR__ . '/../' . '../uploads/' . $filename);

                $stm = DB::getDB()->prepare("insert into files(id, origName, filename, extension, uploadDate, type, password, deletePassword, deleteDate, integrity, fileSize) 
values(:fileid, :origname, :filename, :extension, NOW(), :type, :password, :dpassword, :ddate, :integrity, :filesize)");
                $stm->bindValue(":fileid",$id, PDO::PARAM_STR);
                $stm->bindValue(":origname",$origname, PDO::PARAM_STR);
                $stm->bindValue(":filename",$filename, PDO::PARAM_STR);
                $stm->bindValue(":extension",$extension, PDO::PARAM_STR);
                $stm->bindValue(":type",substr($results['mime'], 0, 64), PDO::PARAM_STR);
                $stm->bindValue(":password",$hpassword, PDO::PARAM_STR);
                $stm->bindValue(":dpassword",hash("sha256", $deletePassword), PDO::PARAM_STR);
                $stm->bindValue(":ddate",date("Y-m-d H:i:s", $deleteDate));
                $stm->bindValue(":integrity",$fileHash, PDO::PARAM_STR);
                $stm->bindValue(":filesize",$fileSize, PDO::PARAM_INT);
                $stm->execute();

                $downloadUrl = IOHelper::getProto() . $_SERVER['SERVER_NAME'] . $this->router->pathFor("download", [
                            'id' => $id
                ]);

                $deleteUrl = IOHelper::getProto() . $_SERVER['SERVER_NAME'] . $this->router->pathFor("delete", [
                            'id' => $id,
                            'deletePassword' => $deletePassword
                ]);

                $totalsize = IOHelper::get_total_size(__DIR__ . "/../../uploads");
                DB::setConfig(DB::$histoTotalFileSize, $totalsize);

                $totalFilesUploaded = (int) DB::getConfig(DB::$histoTotalFileUpload);
                ++$totalFilesUploaded;
                DB::setConfig(DB::$histoTotalFileUpload, $totalFilesUploaded);

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
