<?php

namespace Bzzix\PageBuilder\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageBuilderController extends Controller
{

    public function __construct()
    {
    }

    public function getIndex(Request $request)
    {

        $blocks = [];

        $blocksPath = resource_path('views/blocks');

        $categoryFolders = File::directories($blocksPath);

        foreach ($categoryFolders as $categoryFolder) {
            $categoryName = basename($categoryFolder);

            $blockFiles = File::files($categoryFolder);

            foreach ($blockFiles as $file) {
                $viewPath = 'blocks.' . $categoryName . '.' . $file->getFilenameWithoutExtension();

                // تهيئة المتغير $block داخل كل ملف blade
                $blockData = null;
                // باستخدام طريقة رفع متغيرات باستخدام view()->make و share مؤقت مثلاً:
                view()->share('block', null); // تنظيف سابق

                $output = view($viewPath)->render();

                // بعد الرندر المفروض يكون $block موجود في الـ view
                $blockData = view()->shared('block');

                if (!$blockData) {
                    // اذا لم يتم تعريف $block داخل الملف نحدد افتراضياً
                    $blockData = [
                        'id' => $categoryName . '-' . $file->getFilenameWithoutExtension(),
                        'label' => ucfirst($file->getFilenameWithoutExtension()),
                        'category' => $categoryName,
                        'traits' => [],
                        'script' => ''
                    ];
                } else {
                    // تأكد أن 'category' في $blockData تساوي اسم الفولدر
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