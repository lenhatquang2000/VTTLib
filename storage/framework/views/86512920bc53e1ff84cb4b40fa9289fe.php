<?php
    // Kiểm tra xem có đang ở trang chủ không
    // 1. Kiểm tra qua route name
    // 2. Kiểm tra qua query param preview_template
    // 3. Kiểm tra qua siteNode node_code
    $isHome = request()->routeIs('site.home') || 
               request()->query('preview_template') === 'home' || 
               (isset($siteNode) && $siteNode->node_code === 'home') ||
               (isset($node) && $node->node_code === 'home') ||
               request()->path() === '/';
               
    $showLoader = $isHome;
?>

<?php if($showLoader): ?>
<!-- LOG: Loader condition met. Route: <?php echo e(request()->route()->getName()); ?> Path: <?php echo e(request()->path()); ?> -->
<style>
    /* Book Opening Loader */
    .book-loader-container {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, #450000 0%, #200000 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.8s cubic-bezier(0.645, 0.045, 0.355, 1), visibility 0.6s;
        overflow: hidden;
    }
    .book-loader-container.hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        display: none !important;
    }

    /* Flying Papers Effect */
    .papers-container {
        position: absolute;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    .paper {
        position: absolute;
        width: 30px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 2px;
        animation: fly-paper linear infinite;
    }
    @keyframes fly-paper {
        0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
        10% { opacity: 0.8; }
        90% { opacity: 0.8; }
        100% { transform: translate(var(--x), var(--y)) rotate(var(--r)); opacity: 0; }
    }

    /* 3D Book */
    .book-scene {
        perspective: 1200px;
        animation: zoom-into-book 1.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes zoom-into-book {
        0% { transform: scale(0.6) rotateY(-5deg); opacity: 0; }
        20% { transform: scale(1) rotateY(0deg); opacity: 1; }
        80% { transform: scale(1) rotateY(0deg); opacity: 1; }
        100% { transform: scale(15) rotateY(0deg); opacity: 0; }
    }
    .book-3d {
        width: 280px;
        height: 380px;
        position: relative;
        transform-style: preserve-3d;
        animation: book-hover 3s ease-in-out infinite;
    }
    @keyframes book-hover {
        0%, 100% { transform: rotateY(-25deg) rotateX(5deg); }
        50% { transform: rotateY(-20deg) rotateX(3deg); }
    }
    .book-cover-front {
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #7B0000 0%, #450000 100%);
        border-radius: 4px 16px 16px 4px;
        transform: translateZ(15px);
        box-shadow: 0 0 40px rgba(123, 0, 0, 0.4), inset 0 0 80px rgba(255,255,255,0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 30px;
        border-left: 6px solid #450000;
        overflow: hidden;
    }
    .book-cover-front::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -150%;
        width: 100%;
        height: 200%;
        background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 70%, transparent 100%);
        transform: rotate(25deg);
        animation: light-streak 2s ease-in-out infinite;
        z-index: 10;
    }
    @keyframes light-streak {
        0% { left: -150%; }
        50% { left: 150%; }
        100% { left: 150%; }
    }
    .book-cover-front .book-icon {
        font-size: 64px;
        color: #FFD700;
        margin-bottom: 24px;
        filter: drop-shadow(0 4px 20px rgba(0,0,0,0.3));
    }
    .book-cover-front .book-title {
        font-size: 22px;
        font-weight: 900;
        color: #FFD700;
        letter-spacing: 4px;
        text-transform: uppercase;
        text-align: center;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .book-cover-front .book-subtitle {
        font-size: 10px;
        font-weight: 700;
        color: rgba(255, 215, 0, 0.7);
        letter-spacing: 6px;
        text-transform: uppercase;
        margin-top: 12px;
    }
    .book-cover-front .book-line {
        width: 60px;
        height: 2px;
        background: #FFD700;
        margin: 16px 0;
        border-radius: 2px;
        opacity: 0.5;
    }
    .book-spine {
        position: absolute;
        width: 30px;
        height: 100%;
        background: linear-gradient(180deg, #7B0000, #450000);
        transform: rotateY(-90deg) translateZ(0px) translateX(-15px);
        border-radius: 4px 0 0 4px;
    }
    .book-cover-back {
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #7B0000, #450000);
        border-radius: 4px 16px 16px 4px;
        transform: translateZ(-15px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .book-pages-edge {
        position: absolute;
        width: calc(100% - 10px);
        height: 26px;
        background: repeating-linear-gradient(90deg, #e2e8f0 0px, #e2e8f0 2px, #f8fafc 2px, #f8fafc 4px);
        left: 5px;
        transform: rotateX(90deg) translateZ(190px) translateY(-13px);
    }
    .loader-text {
        position: absolute;
        bottom: 15%;
        color: #FFD700;
        font-family: sans-serif;
        font-weight: 900;
        letter-spacing: 8px;
        text-transform: uppercase;
        font-size: 14px;
        text-align: center;
        left: 50%;
        transform: translateX(-50%);
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        animation: text-pulse 2s ease-in-out infinite;
    }
    @keyframes text-pulse { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
</style>

<div id="pageLoader" class="book-loader-container">
    <div class="papers-container" id="papersContainer"></div>
    <div class="book-scene">
        <div class="book-3d">
            <div class="book-cover-front">
                <i class="fas fa-book-open book-icon"></i>
                <div class="book-line"></div>
                <div class="book-title">VTTLib</div>
                <div class="book-subtitle">Digital Library</div>
            </div>
            <div class="book-spine"></div>
            <div class="book-pages-edge"></div>
            <div class="book-cover-back"></div>
        </div>
    </div>
    <div class="loader-text">Entering the world of knowledge...</div>
</div>

<script>
    (function() {
        console.log('Book Loader script inline starting...');
        const loader = document.getElementById('pageLoader');
        if (!loader) {
            console.error('Loader element #pageLoader not found!');
            return;
        }

        function hideLoader(source) {
            console.log('hideLoader called from: ' + source);
            if (loader.getAttribute('data-hidden') === 'true') {
                console.log('Loader already hidden, skipping.');
                return;
            }
            
            loader.setAttribute('data-hidden', 'true');
            loader.style.opacity = '0';
            loader.style.transition = 'opacity 0.8s ease-out';
            
            setTimeout(() => {
                loader.classList.add('hidden');
                loader.style.display = 'none';
                loader.style.zIndex = '-9999';
                loader.style.pointerEvents = 'none';
                console.log('Loader completely removed from view.');
            }, 850);
        }

        function initLoader() {
            console.log('initLoader starting...');
            const container = document.getElementById('papersContainer');
            if (container) {
                console.log('Generating papers...');
                for (let i = 0; i < 15; i++) {
                    const paper = document.createElement('div');
                    paper.className = 'paper';
                    const startX = Math.random() * window.innerWidth;
                    const startY = Math.random() * window.innerHeight;
                    const destX = (Math.random() - 0.5) * 400;
                    const destY = (Math.random() - 0.5) * 400;
                    const rotation = Math.random() * 720;
                    const duration = 2 + Math.random() * 3;
                    const delay = Math.random() * 2;
                    paper.style.left = startX + 'px';
                    paper.style.top = startY + 'px';
                    paper.style.setProperty('--x', destX + 'px');
                    paper.style.setProperty('--y', destY + 'px');
                    paper.style.setProperty('--r', rotation + 'deg');
                    paper.style.animationDuration = duration + 's';
                    paper.style.animationDelay = delay + 's';
                    container.appendChild(paper);
                }
            } else {
                console.warn('papersContainer not found.');
            }
            
            console.log('Scheduling normal hide in 2.2s');
            setTimeout(() => hideLoader('normal_timeout'), 2200);
        }

        console.log('Document readyState: ' + document.readyState);
        if (document.readyState === 'interactive' || document.readyState === 'complete') {
            initLoader();
        } else {
            window.addEventListener('DOMContentLoaded', () => {
                console.log('DOMContentLoaded fired');
                initLoader();
            });
        }

        // Emergency backup
        window.addEventListener('load', () => {
            console.log('Window Load fired');
            setTimeout(() => hideLoader('window_load_event'), 500);
        });

        console.log('Scheduling emergency hide in 4s');
        setTimeout(() => hideLoader('emergency_timeout'), 4000);
    })();
</script>
<?php endif; ?>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/partials/book-loader.blade.php ENDPATH**/ ?>