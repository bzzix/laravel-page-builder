<?php

namespace Bzzix\PageBuilder\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{

    public function __construct()
    {
    }


    public function getIndex(Request $request)
    {
        $blocks = [];

        // جرّب أولاً المسار المنشور (بعد النسخ باستخدام vendor:publish)
        $publishedBlocksPath = resource_path('views/vendor/bzzix-pagebuilder/blocks');

        // اختر المسار الموجود فعليًا
        $blocksPath = File::exists($publishedBlocksPath) ? $publishedBlocksPath : null;

        // إذا لم يوجد أي مسار، أعِد الصفحة بدون بلوكات
        if (!$blocksPath) {
            return view('bzzix-pagebuilder::index', compact('blocks'));
        }

        $categoryFolders = File::directories($blocksPath);

        foreach ($categoryFolders as $categoryFolder) {
            $categoryName = basename($categoryFolder);

            $blockFiles = File::files($categoryFolder);

            if (empty($blockFiles)) {
                continue;
            }

            foreach ($blockFiles as $file) {
                $filename = Str::before($file->getFilename(), '.blade.php');

                $viewPath = 'vendor.bzzix-pagebuilder.blocks.' . $categoryName . '.' . $filename;

                // تنظيف متغير block من أي مشاركة سابقة
                view()->share('block', null);

                // تنفيذ العرض لجلب المتغير $block إن وُجد
                $output = view($viewPath)->render();

                $blockData = view()->shared('block');

                if (!$blockData) {
                    $blockData = [
                        'id' => $categoryName . '-' . $filename,
                        'label' => ucfirst($filename),
                        'category' => $categoryName,
                        'traits' => [],
                        'script' => ''
                    ];
                } else {
                    // ضبط التصنيف بشكل صحيح
                    $blockData['category'] = $categoryName;
                }

                $blocks[] = [
                    'id' => $blockData['id'],
                    'label' => $blockData['label'],
                    'category' => $blockData['category'],
                    'traits' => $blockData['traits'],
                    'script' => $blockData['script'],
                    'content' => $output
                ];
            }
        }

        return view('bzzix-pagebuilder::index', compact('blocks'));
    }



    public function postIndex(Request $request)
    {
    }

    public function getUpdate()
    {
        return view('bzzix-pagebuilder::update');
    }

    public function postUpdate(Request $request, $slug)
    {

    }

}