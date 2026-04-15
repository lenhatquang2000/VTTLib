<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Thư viện số'); ?></title>
    
    <!-- SEO Meta Tags -->
    <?php if(isset($node)): ?>
        <?php $__env->startSection('meta-description'); ?>
            <meta name="description" content="<?php echo e($node->meta_description ?: Str::limit(strip_tags($node->content ?? ''), 160)); ?>">
        <?php echo $__env->yieldSection(); ?>
        <?php $__env->startSection('meta-keywords'); ?>
            <meta name="keywords" content="<?php echo e($node->meta_keywords ?: 'thư viện, số, quản lý, sách'); ?>">
        <?php echo $__env->yieldSection(); ?>
    <?php else: ?>
        <meta name="description" content="Thư viện số - Nền tảng quản lý thư viện hiện đại">
        <meta name="keywords" content="thư viện, số, quản lý, sách, OPAC">
    <?php endif; ?>
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        .prose {
            max-width: none;
        }
        .prose h1, .prose h2, .prose h3 {
            color: #1f2937;
            font-weight: bold;
        }
        .prose h1 { font-size: 2.5rem; margin-top: 2rem; margin-bottom: 1rem; }
        .prose h2 { font-size: 2rem; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .prose h3 { font-size: 1.5rem; margin-top: 1.25rem; margin-bottom: 0.5rem; }
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        .prose li {
            margin-bottom: 0.5rem;
        }
        .prose a {
            color: #2563eb;
            text-decoration: none;
        }
        .prose a:hover {
            text-decoration: underline;
        }
        .prose blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: #6b7280;
        }
        .prose code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                        <span class="font-bold text-xl text-gray-800">Thư viện số</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <?php if(isset($menuItems)): ?>
                        <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item->can_access ?? true): ?>
                                <a href="<?php echo e($item->getUrl()); ?>" 
                                   class="text-gray-600 hover:text-blue-600 transition"
                                   <?php if($item->target === '_blank'): ?> target="_blank" <?php endif; ?>>
                                    <?php echo e($item->display_name); ?>

                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <?php if(isset($menuItems)): ?>
                    <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->can_access ?? true): ?>
                            <a href="<?php echo e($item->getUrl()); ?>" 
                               class="block py-2 text-gray-600 hover:text-blue-600 transition"
                               <?php if($item->target === '_blank'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e($item->display_name); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-book-open text-blue-400 text-xl"></i>
                        <span class="font-bold text-lg">Thư viện số</span>
                    </div>
                    <p class="text-gray-400">
                        Nền tảng quản lý thư viện hiện đại, hiệu quả và toàn diện.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4">Liên kết nhanh</h3>
                    <?php if(isset($menuItems)): ?>
                        <ul class="space-y-2">
                            <?php $__currentLoopData = $menuItems->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->can_access ?? true): ?>
                                    <li>
                                        <a href="<?php echo e($item->getUrl()); ?>" 
                                           class="text-gray-400 hover:text-white transition"
                                           <?php if($item->target === '_blank'): ?> target="_blank" <?php endif; ?>>
                                            <?php echo e($item->display_name); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="font-semibold mb-4">Dịch vụ</h3>
                    <?php if(isset($footerItems)): ?>
                        <ul class="space-y-2">
                            <?php $__currentLoopData = $footerItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($item->can_access ?? true): ?>
                                    <li>
                                        <a href="<?php echo e($item->getUrl()); ?>" 
                                           class="text-gray-400 hover:text-white transition"
                                           <?php if($item->target === '_blank'): ?> target="_blank" <?php endif; ?>>
                                            <?php echo e($item->display_name); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-semibold mb-4">Liên hệ</h3>
                    <?php if(isset($footerItems)): ?>
                        <?php
                            $contactNode = $footerItems->firstWhere('node_code', 'lien-he');
                        ?>
                        <?php if($contactNode && $contactNode->content): ?>
                            <div class="prose prose-sm text-gray-400">
                                <?php echo $contactNode->content; ?>

                            </div>
                        <?php else: ?>
                            <ul class="space-y-2 text-gray-400">
                                <li class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    123 Đường ABC, Quận 1, TP.HCM
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    (028) 1234 5678
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    info@thuvienso.vn
                                </li>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> Thư viện số. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/layouts/site.blade.php ENDPATH**/ ?>