<?php

use Carbon\Carbon;
use App\Domains\System\Models\Pool;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     *
     * @param $time
     *
     * @return Carbon
     * @throws Exception
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function homeRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return 'admin.claim.project.index';
            }

            if (auth()->user()->isUser()) {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if(! function_exists('current_user_role')) {
    /**
    * Return the role name of logged in user
    *
    * @return string
    */
    function current_user_role() {
        return auth()->user()->roles()->first()->name;
    }
}

if(! function_exists('current_user_pools')) {
    /**
    * Return the pools of logged in user
    *
    * @return string
    */
    function current_user_pools() {
        if(current_user_role() == 'Administrator' || current_user_role() == 'Super User') {
            return Pool::all();
        }
        return auth()->user()->pools()->get();
    }
}

if(! function_exists('get_months_list')) {
    /**
    * Return the months
    *
    * @return array
    */
    function months() {
        $months= [];
        for ($month = 1; $month <= 12; $month++) {
            $months[str_pad($month, 2, '0', STR_PAD_LEFT)] = str_pad($month, 2, '0', STR_PAD_LEFT);
        }
        return $months;
    }
}

if(! function_exists('years')) {
    /**
    * Return the months
    *
    * @return array
    */
    function years() {
        $years= [];
        for ($year = (date('Y') - 15); $year <= (date('Y') + 20); $year++) {
            $years[$year] = $year;
        }
        return $years;
    }
}

/**
 * Scales an image with save aspect ration for X, Y or both axes.
 *
 * @param string $sourceFile Absolute path to source image.
 * @param string $destinationFile Absolute path to scaled image.
 * @param int|null $toWidth Maximum `width` of scaled image.
 * @param int|null $toHeight Maximum `height` of scaled image.
 * @param int|null $percent Percent of scale of the source image's size.
 * @param int $scaleAxis Determines how of axis will be used to scale image.
 *
 * May take a value of {@link IMAGES_SCALE_AXIS_X}, {@link IMAGES_SCALE_AXIS_Y} or {@link IMAGES_SCALE_AXIS_BOTH}.
 * @return bool True on success or False on failure.
 */
if(! function_exists('scaleImage')) {
    function scaleImage($sourceFile, $destinationFile, $toWidth = null, $toHeight = null, $percent = null, $scaleAxis = IMAGES_SCALE_AXIS_BOTH) {
        $toWidth = (int)$toWidth;
        $toHeight = (int)$toHeight;
        $percent = (int)$percent;
        $result = false;

        if (($toWidth | $toHeight | $percent)
            && file_exists($sourceFile)
            && (file_exists(dirname($destinationFile)) || mkdir(dirname($destinationFile), 0777, true))) {

            $mime = getimagesize($sourceFile);

            if (in_array($mime['mime'], ['image/jpg', 'image/jpeg', 'image/pjpeg'])) {
                $src_img = imagecreatefromjpeg($sourceFile);
            } elseif ($mime['mime'] == 'image/png') {
                $src_img = imagecreatefrompng($sourceFile);
            }

            $original_width = imagesx($src_img);
            $original_height = imagesy($src_img);

            if ($scaleAxis == IMAGES_SCALE_AXIS_BOTH) {
                if (!($toWidth | $percent)) {
                    $scaleAxis = IMAGES_SCALE_AXIS_Y;
                } elseif (!($toHeight | $percent)) {
                    $scaleAxis = IMAGES_SCALE_AXIS_X;
                }
            }

            if ($scaleAxis == IMAGES_SCALE_AXIS_X && $toWidth) {
                $scale_ratio = $original_width / $toWidth;
            } elseif ($scaleAxis == IMAGES_SCALE_AXIS_Y && $toHeight) {
                $scale_ratio = $original_height / $toHeight;
            } elseif ($percent) {
                $scale_ratio = 100 / $percent;
            } else {
                $scale_ratio_width = $original_width / $toWidth;
                $scale_ratio_height = $original_height / $toHeight;

                if ($original_width / $scale_ratio_width < $toWidth && $original_height / $scale_ratio_height < $toHeight) {
                    $scale_ratio = min($scale_ratio_width, $scale_ratio_height);
                } else {
                    $scale_ratio = max($scale_ratio_width, $scale_ratio_height);
                }
            }

            $scale_width = $original_width / $scale_ratio;
            $scale_height = $original_height / $scale_ratio;

            $dst_img = imagecreatetruecolor($scale_width, $scale_height);

            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $scale_width, $scale_height, $original_width, $original_height);

            if (in_array($mime['mime'], ['image/jpg', 'image/jpeg', 'image/pjpeg'])) {
                $result = imagejpeg($dst_img, $destinationFile, JPEG_COMPRESSION_QUALITY);
            } elseif ($mime['mime'] == 'image/png') {
                $result = imagepng($dst_img, $destinationFile, PNG_COMPRESSION_QUALITY);
            }

            imagedestroy($dst_img);
            imagedestroy($src_img);
        }

        return $result;
    }
}
