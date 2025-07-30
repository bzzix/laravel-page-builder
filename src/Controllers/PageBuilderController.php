<?php

namespace Bzzix\PageBuilder\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Bzzix\PageBuilder\Models\Page;

class PageBuilderController extends Controller
{

    public function __construct()
    {
    }


    public function getIndex(Request $request)
    {
        $blocks = [];
        $statuses = [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'scheduled' => 'مجدول',
            'private' => 'خاص'
        ];

        // جرّب أولاً المسار المنشور (بعد النسخ باستخدام vendor:publish)
        $publishedBlocksPath = resource_path('views/vendor/bzzix-pagebuilder/blocks');

        // اختر المسار الموجود فعليًا
        $blocksPath = File::exists($publishedBlocksPath) ? $publishedBlocksPath : null;

        // إذا لم يوجد أي مسار، أعِد الصفحة بدون بلوكات
        if (!$blocksPath) {
            return view('bzzix-pagebuilder::index', compact('blocks', 'statuses'));
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

        $statuses = [
            'draft' => 'مسودة',
            'published' => 'منشور',
            'scheduled' => 'مجدول',
            'private' => 'خاص'
        ];
        
        return view('bzzix-pagebuilder::index', compact('blocks', 'statuses'));
    }



    public function postIndex(Request $request)
    {        
        try {
            // التحقق من البيانات
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255',
                'status' => 'required|string|in:draft,published,scheduled,private',
                'html' => 'required|string',
                'css' => 'nullable|string',
                'components' => 'nullable',
                'content' => 'nullable|string',
            ]);

            $user = $request->user();

            if (!class_exists(Page::class)) {
                throw new \Exception('Page model not found. Please check if the package is properly installed.');
            }

            // إنشاء الصفحة مع البيانات المرسلة
            $page = Page::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'] ?? Str::slug($validated['title']),
                'content' => $validated['html'], // نستخدم HTML كمحتوى
                'status' => $validated['status'],
                'user_id' => $user ? $user->id : null,
                'html' => $validated['html'],
                'css' => $validated['css'],
                'components' => $validated['components'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الصفحة بنجاح',
                'page' => $page,
                'redirect' => route(config('bzzix-pagebuilder.update_route'), ['slug' => $page->slug])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function getUpdate($id)
    {
        $page = Page::where('slug', $id)->firstOrFail();
        return view('bzzix-pagebuilder::update', compact('page'));
    }

    public function postUpdate(Request $request, $slug)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'status' => 'nullable|string',
                'html' => 'nullable|string',
                'css' => 'nullable|string',
                'components' => 'nullable',
            ]);

            $page = Page::where('slug', $slug)->firstOrFail();

            $page->update([
                'title' => $validated['title'] ?? $page->title,
                'content' => $validated['html'] ?? $page->html,
                'status' => $validated['status'] ?? $page->status,
                'html' => $validated['html'] ?? $page->html,
                'css' => $validated['css'] ?? $page->css,
                'components' => $validated['components'] ?? $page->components,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الصفحة بنجاح',
                'page' => $page,
                'redirect' => route('page.edit', ['slug' => $page->slug])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

}