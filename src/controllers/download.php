<?php

$app->map(['GET', 'POST'], '/download-{id:[a-z0-9]{8,10}}[-{password:[a-z0-9]{8,14}}]', function ($request, $response, $args) {

    // CSRF token name and value
    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);

    $fileid = $args['id'];
    $file = FilesQuery::create()->filterById($fileid)->findOne();

    if ($file != null) {

        $filename = $file->getFilename();

        if (file_exists(__DIR__ . '/../' . '../uploads/' . $filename)) {

            $needspassword = (!empty($file->getPassword())) ? true : false;

            $downloadfilename = !empty($file->getOrigname()) ? $file->getOrigname() : $file->getFilename() . '.' . $file->getExtension();

            $deleteDate = IOHelper::getStringCountdownDelete($file->getDeletedate()->getTimestamp());

            if (!$needspassword) {
                if ($request->getParam('download') == "yes") {

                    IOHelper::download($file, __DIR__ . '/../' . '../uploads/', $this);
                } else {
                    return $this->view->render($response, 'download.html', [
                                'page' => 'download',
                                'needspassword' => $needspassword,
                                'nameKey' => $nameKey,
                                'name' => $name,
                                'valueKey' => $valueKey,
                                'value' => $value,
                                'filetype' => $file->getType(),
                                'filename' => $downloadfilename,
                                'filesize' => filesize(__DIR__ . '/../' . '../uploads/' . $filename),
                                'filesha1' => $file->getIntegrity(),
                                'deleteDate' => $deleteDate
                    ]);
                }
            } else if ($needspassword) {

                $urlPass = @$args['password'];

                $inputPassword = (!empty($request->getParam('txtDownloadPassword'))) ? $request->getParam('txtDownloadPassword') : $urlPass;

                if (empty($inputPassword)) {
                    return $this->view->render($response, 'download.html', [
                                'page' => 'download',
                                'needspassword' => $needspassword,
                                'nameKey' => $nameKey,
                                'name' => $name,
                                'valueKey' => $valueKey,
                                'value' => $value
                    ]);
                } else {

                    $ph = hash("sha256", $inputPassword);

                    if (hash_equals($file->getPassword(), $ph)) {

                        $incorrectPassword = false;

                        if ($request->getParam('download') == "yes") {

                            IOHelper::download($file, __DIR__ . '/../' . '../uploads/', $this);
                        } else {

                            return $this->view->render($response, 'download.html', [
                                        'page' => 'download',
                                        'needspassword' => $needspassword,
                                        'password' => $inputPassword,
                                        'incorrectpassword' => $incorrectPassword,
                                        'nameKey' => $nameKey,
                                        'name' => $name,
                                        'valueKey' => $valueKey,
                                        'value' => $value,
                                        'filetype' => $file->getType(),
                                        'filename' => $downloadfilename,
                                        'filesize' => filesize(__DIR__ . '/../' . '../uploads/' . $filename),
                                        'filesha1' => $file->getIntegrity(),
                                        'deleteDate' => $deleteDate
                            ]);
                        }
                    } else {
                        $incorrectPassword = true;

                        return $this->view->render($response, 'download.html', [
                                    'page' => 'download',
                                    'needspassword' => $needspassword,
                                    'incorrectpassword' => $incorrectPassword,
                                    'nameKey' => $nameKey,
                                    'name' => $name,
                                    'valueKey' => $valueKey,
                                    'value' => $value
                        ]);
                    }
                }
            }
        } else {
            $file->delete();

            return $this->view->render($response, 'download-error.html', [
                        'page' => 'download',
                        'errormsg' => 'This file ' . $file->getId() . ' does not exists anymore'
            ]);
        }
    } else {
        return $this->view->render($response, 'download-error.html', [
                    'page' => 'download',
                    'errormsg' => 'This file ' . $args['id'] . ' does not exists'
        ]);
    }
})->setName("download");
