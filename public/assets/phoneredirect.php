<?php
    if ($_POST["query"] == "phoneRedirect"){
        $countPhoneFiles = 0;       // Кол-во HTML файлов в мобильной версии
        $countTabletFiles = 0;      // Кол-во HTML файлов в планшетной версии
        $countDesktopFiles = 0;     // Кол-во HTML файлов в десктопной версии

        // ---------------------------------------------------------------------
        // Ф-я редиректа, которая будет подставляться в страницы
        $part_01 = "(function(){var tablet = ";

        if ($_POST["version"] == "phone"){$part_02 = "false;";}
        if (($_POST["version"] == "tablet") || ($_POST["version"] == "phoneAndTablet")){$part_02 = "true;";}

        $part_03 = " var phoneWidth = " . $_POST["widthPhone"] . "; ";
        $part_04 = " var tabletWidth = " . $_POST["widthTablet"] . "; ";
        $part_05 = " var desktopWidth = " . $_POST["widthDesktop"] . "; ";

        $part_06 = 'var addressPreviousPage = document.referrer; var screenWidth = screen.width; var posEndSlash = (window.location.href).lastIndexOf("/"); var strPart_1 = (window.location.href).substr(0, posEndSlash + 1); var strPart_2 = (window.location.href).substr(posEndSlash); var redirect = true; if (addressPreviousPage != ""){addressPreviousPage = addressPreviousPage.replace("?devicelock=phone&","?"); addressPreviousPage = addressPreviousPage.replace("?devicelock=phone",""); addressPreviousPage = addressPreviousPage.replace("devicelock=phone",""); var hostnamePreviousPage = new URL(addressPreviousPage).hostname; if (window.location.hostname == hostnamePreviousPage){ redirect = false;}} if (tablet === true){if ((screenWidth < desktopWidth) && (screenWidth >= tabletWidth) && (redirect == true)){var addressRedirect = strPart_1 + "tablet" + strPart_2; document.location.href = addressRedirect;} if ((screenWidth < tabletWidth) && (redirect == true)){var addressRedirect = strPart_1 + "phone" + strPart_2; document.location.href = addressRedirect;}} else {if ((screenWidth < desktopWidth) && (redirect == true)){var addressRedirect = strPart_1 + "phone" + strPart_2; document.location.href = addressRedirect;}}})();';

        $myFunc = $part_01 . $part_02 . $part_03 . $part_04 . $part_05 . $part_06;

        // ---------------------------------------------------------------------
        // Читаем html файлы в десктопной версии
        if (($_POST["version"] == "phone") || ($_POST["version"] == "phoneAndTablet")){
            $filelist = glob("../phone/*.html");
            $countPhoneFiles = count($filelist);

            for ($i = 0; $i < $countPhoneFiles;  $i++){
                $fileStr = file_get_contents($filelist[$i]);

                $posBeginFunc = strpos($fileStr, "(function(c,b,d){var a=function(){if(navigator.maxTouchPoints>1)");
                $posEndFunc = strpos($fileStr, "// Update the 'nojs'/'js' class on the html node") - 1;

                if (($posBeginFunc !== false) && ($posEndFunc !== false)){
                    $filePart_1 = substr($fileStr, 0, $posBeginFunc);
                    $filePart_2 = substr($fileStr, $posEndFunc);
                    $fileStr = $filePart_1 . $myFunc . $filePart_2;
                }

                $fileStr = str_replace('?devicelock=phone&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=phone', '', $fileStr);
                $fileStr = str_replace('devicelock=phone', '', $fileStr);

                $fileStr = str_replace('?devicelock=tablet&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=tablet', '', $fileStr);
                $fileStr = str_replace('devicelock=tablet', '', $fileStr);

                $fileStr = str_replace('?devicelock=desktop&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=desktop', '', $fileStr);
                $fileStr = str_replace('devicelock=desktop', '', $fileStr);

                file_put_contents($filelist[$i], $fileStr);
            }
        }

        // Читаем html файлы в планшетной версии
        if (($_POST["version"] == "tablet") || ($_POST["version"] == "phoneAndTablet")){
            $filelist = glob("../tablet/*.html");
            $countTabletFiles = count($filelist);

            for ($i = 0; $i < $countTabletFiles;  $i++){
                $fileStr = file_get_contents($filelist[$i]);

                $posBeginFunc = strpos($fileStr, "(function(c,b,d){var a=function(){if(navigator.maxTouchPoints>1)");
                $posEndFunc = strpos($fileStr, "// Update the 'nojs'/'js' class on the html node") - 1;

                if (($posBeginFunc !== false) && ($posEndFunc !== false)){
                    $filePart_1 = substr($fileStr, 0, $posBeginFunc);
                    $filePart_2 = substr($fileStr, $posEndFunc);
                    $fileStr = $filePart_1 . $myFunc . $filePart_2;
                }

                $fileStr = str_replace('?devicelock=phone&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=phone', '', $fileStr);
                $fileStr = str_replace('devicelock=phone', '', $fileStr);

                $fileStr = str_replace('?devicelock=tablet&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=tablet', '', $fileStr);
                $fileStr = str_replace('devicelock=tablet', '', $fileStr);

                $fileStr = str_replace('?devicelock=desktop&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=desktop', '', $fileStr);
                $fileStr = str_replace('devicelock=desktop', '', $fileStr);

                file_put_contents($filelist[$i], $fileStr);
            }
        }

        // Читаем html файлы в десктопной версии
        if (($_POST["version"] == "phone") || ($_POST["version"] == "tablet") || ($_POST["version"] == "phoneAndTablet")){
            $filelist = glob("../*.html");
            $countDesktopFiles = count($filelist);

            for ($i = 0; $i < $countDesktopFiles;  $i++){
                $fileStr = file_get_contents($filelist[$i]);

                $posBeginFunc = strpos($fileStr, "(function(c,b,d){var a=function(){if(navigator.maxTouchPoints>1)");
                $posEndFunc = strpos($fileStr, "// Update the 'nojs'/'js' class on the html node") - 1;

                if (($posBeginFunc !== false) && ($posEndFunc !== false)){
                    $filePart_1 = substr($fileStr, 0, $posBeginFunc);
                    $filePart_2 = substr($fileStr, $posEndFunc);
                    $fileStr = $filePart_1 . $myFunc . $filePart_2;
                }

                $fileStr = str_replace('?devicelock=phone&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=phone', '', $fileStr);
                $fileStr = str_replace('devicelock=phone', '', $fileStr);

                $fileStr = str_replace('?devicelock=tablet&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=tablet', '', $fileStr);
                $fileStr = str_replace('devicelock=tablet', '', $fileStr);

                $fileStr = str_replace('?devicelock=desktop&', '?', $fileStr);
                $fileStr = str_replace('?devicelock=desktop', '', $fileStr);
                $fileStr = str_replace('devicelock=desktop', '', $fileStr);

                file_put_contents($filelist[$i], $fileStr);
            }
        }

        echo "ok";
    }
?>