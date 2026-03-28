@extends('layouts.admin')

@section('title', __('Sidebar Management'))

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    /* ============================================
       BASE RESET & CUSTOM PROPERTIES
    ============================================ */
    :root {
        --clr-bg:        #080b14;
        --clr-surface:   #0d1117;
        --clr-glass:     rgba(255,255,255,0.04);
        --clr-border:    rgba(139,92,246,0.18);
        --clr-border-hl: rgba(139,92,246,0.50);
        --clr-violet:    #8b5cf6;
        --clr-indigo:    #6366f1;
        --clr-pink:      #ec4899;
        --clr-cyan:      #06b6d4;
        --clr-emerald:   #10b981;
        --clr-amber:     #f59e0b;
        --clr-text:      #f1f5f9;
        --clr-text-sub:  #94a3b8;
        --clr-text-dim:  #475569;
        --radius-xl:     20px;
        --radius-lg:     14px;
        --radius-md:     10px;
        --shadow-sm:     0 2px 8px rgba(0,0,0,.4);
        --shadow-lg:     0 20px 50px rgba(0,0,0,.6);
        --shadow-glow:   0 0 30px rgba(139,92,246,.25);
        --transition:    0.28s cubic-bezier(.4,0,.2,1);
    }

    /* Override main background */
    main {
        background: radial-gradient(ellipse at 0% 0%, #13102a 0%, #080b14 45%, #0a1220 100%) !important;
        min-height: 100vh;
        padding: 2rem !important;
        font-family: 'Inter', sans-serif !important;
        position: relative;
        overflow-x: hidden;
    }

    /* Ambient orbs */
    main::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        background:
            radial-gradient(circle at 15% 25%, rgba(139,92,246,.12) 0%, transparent 40%),
            radial-gradient(circle at 85% 70%, rgba(99,102,241,.10) 0%, transparent 40%),
            radial-gradient(circle at 50% 90%, rgba(236,72,153,.07) 0%, transparent 35%);
        animation: ambientOrb 20s ease-in-out infinite alternate;
    }

    @keyframes ambientOrb {
        0%   { transform: scale(1) translate(0,0); }
        50%  { transform: scale(1.08) translate(-20px, -15px); }
        100% { transform: scale(.96) translate(18px, 12px); }
    }

    main > * { position: relative; z-index: 1; }

    /* ============================================
       PAGE HEADER
    ============================================ */
    .sbm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.25rem;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        background: rgba(255,255,255,.025);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-xl);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        position: relative;
        overflow: hidden;
    }

    .sbm-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg,
            transparent 0%,
            var(--clr-violet) 25%,
            var(--clr-indigo) 50%,
            var(--clr-pink) 75%,
            transparent 100%);
        animation: shimmer 4s ease infinite;
    }

    @keyframes shimmer {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .sbm-header-left h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--clr-text);
        letter-spacing: -.035em;
        margin: 0 0 .3rem;
        background: linear-gradient(135deg, #e2d9f3 0%, #a78bfa 50%, #818cf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sbm-header-left p {
        color: var(--clr-text-sub);
        margin: 0;
        font-size: .9rem;
        font-weight: 400;
    }

    .sbm-actions { display: flex; gap: .75rem; flex-wrap: wrap; }

    /* ============================================
       BUTTONS
    ============================================ */
    .btn-sbm {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: .85rem;
        padding: .65rem 1.5rem;
        border-radius: var(--radius-lg);
        border: none;
        cursor: pointer;
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .btn-sbm::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 100%);
        opacity: 0;
        transition: opacity var(--transition);
    }

    .btn-sbm:hover::after { opacity: 1; }

    .btn-save {
        background: linear-gradient(135deg, #8b5cf6, #6366f1 50%, #ec4899);
        color: #fff;
        box-shadow: 0 4px 18px rgba(139,92,246,.4);
    }

    .btn-save:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 10px 30px rgba(139,92,246,.55);
        color: #fff;
    }

    .btn-reset {
        background: rgba(255,255,255,.06);
        border: 1.5px solid var(--clr-border);
        color: var(--clr-text-sub);
    }

    .btn-reset:hover {
        background: rgba(139,92,246,.12);
        border-color: var(--clr-border-hl);
        color: var(--clr-text);
        transform: translateY(-2px);
    }

    /* ============================================
       STATS BAR
    ============================================ */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    @media (max-width: 900px) {
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 500px) {
        .stats-bar { grid-template-columns: 1fr; }
    }

    .stat-card {
        padding: 1.25rem 1.5rem;
        border-radius: var(--radius-xl);
        border: 1px solid var(--clr-border);
        background: var(--clr-glass);
        backdrop-filter: blur(20px);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all var(--transition);
        cursor: default;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--clr-border-hl);
        box-shadow: var(--shadow-glow);
    }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .stat-icon.violet  { background: rgba(139,92,246,.15); color: #a78bfa; }
    .stat-icon.emerald { background: rgba(16,185,129,.15);  color: #34d399; }
    .stat-icon.amber   { background: rgba(245,158,11,.15);  color: #fbbf24; }
    .stat-icon.cyan    { background: rgba(6,182,212,.15);   color: #22d3ee; }

    .stat-info { min-width: 0; }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--clr-text);
        line-height: 1;
        letter-spacing: -.03em;
    }

    .stat-label {
        font-size: .78rem;
        font-weight: 500;
        color: var(--clr-text-sub);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-top: .25rem;
    }

    /* ============================================
       HINT BOX
    ============================================ */
    .hint-box {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background: rgba(139,92,246,.06);
        border: 1px solid rgba(139,92,246,.2);
        border-radius: var(--radius-xl);
        padding: 1.1rem 1.5rem;
        margin-bottom: 1.75rem;
        backdrop-filter: blur(12px);
    }

    .hint-box-icon {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(139,92,246,.18);
        color: #a78bfa;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
        margin-top: .05rem;
    }

    .hint-box-content { flex: 1; }

    .hint-box-content h6 {
        font-size: .82rem;
        font-weight: 700;
        color: #c4b5fd;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin: 0 0 .5rem;
    }

    .hint-pills {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .hint-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        padding: .3rem .75rem;
        border-radius: 999px;
        font-size: .78rem;
        color: var(--clr-text-sub);
        font-weight: 500;
    }

    .hint-pill i { font-size: .7rem; color: #8b5cf6; }

    /* ============================================
       TREE PANEL
    ============================================ */
    .tree-panel {
        background: rgba(255,255,255,.025);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-xl);
        backdrop-filter: blur(20px);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .tree-panel-header {
        padding: 1.1rem 1.75rem;
        border-bottom: 1px solid var(--clr-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(255,255,255,.02);
    }

    .tree-panel-header h2 {
        font-size: .9rem;
        font-weight: 700;
        color: var(--clr-text-sub);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .tree-panel-header h2 i { color: var(--clr-violet); }

    .tree-panel-badge {
        background: rgba(139,92,246,.15);
        color: #a78bfa;
        border: 1px solid rgba(139,92,246,.3);
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        padding: .2rem .65rem;
        letter-spacing: .04em;
    }

    .tree-body {
        padding: 1.25rem;
    }

    /* ============================================
       SORTABLE NESTED TREE
    ============================================ */
    .sortable-root {
        min-height: 40px;
        position: relative;
    }

    /* Drop placeholder */
    .sortable-ghost {
        opacity: 0;
    }

    .sortable-chosen {
        opacity: 1 !important;
    }

    .drop-indicator {
        height: 3px;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--clr-violet), var(--clr-pink));
        margin: 4px 0;
        pointer-events: none;
        animation: indicatorPulse .6s ease-in-out infinite alternate;
    }

    @keyframes indicatorPulse {
        0%   { opacity: .6; }
        100% { opacity: 1; box-shadow: 0 0 10px rgba(139,92,246,.6); }
    }

    /* ============================================
       TREE ITEM CARD
    ============================================ */
    .tree-item {
        margin-bottom: .6rem;
    }

    .tree-card {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1rem;
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255,255,255,.07);
        background: rgba(255,255,255,.03);
        transition: all var(--transition);
        position: relative;
        cursor: default;
        user-select: none;
        overflow: hidden;
    }

    .tree-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: linear-gradient(to bottom, var(--clr-violet), var(--clr-indigo));
        opacity: 0;
        transition: opacity var(--transition);
    }

    .tree-card:hover {
        background: rgba(139,92,246,.07);
        border-color: rgba(139,92,246,.25);
        transform: translateX(3px);
    }

    .tree-card:hover::before { opacity: 1; }

    /* When being dragged */
    .tree-item.dragging-active .tree-card {
        background: rgba(139,92,246,.14);
        border-color: var(--clr-violet);
        box-shadow: 0 8px 30px rgba(139,92,246,.35), var(--shadow-glow);
        transform: scale(1.02) rotate(.5deg);
    }

    /* Level indentation */
    .tree-children {
        margin-left: 1.75rem;
        margin-top: .5rem;
        padding-left: 1rem;
        border-left: 2px solid rgba(139,92,246,.2);
        min-height: 30px;
        position: relative;
        transition: border-color var(--transition);
    }

    .tree-children.drag-over-children {
        border-left-color: var(--clr-violet);
        background: rgba(139,92,246,.04);
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
    }

    /* ============================================
       DRAG HANDLE
    ============================================ */
    .drag-handle {
        color: var(--clr-text-dim);
        cursor: grab;
        font-size: .95rem;
        padding: .4rem .45rem;
        border-radius: 8px;
        flex-shrink: 0;
        transition: all var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tree-card:hover .drag-handle {
        color: var(--clr-violet);
        background: rgba(139,92,246,.12);
    }

    .drag-handle:active { cursor: grabbing; }

    /* ============================================
       ITEM ICON
    ============================================ */
    .item-icon-wrap {
        width: 36px; height: 36px;
        border-radius: var(--radius-md);
        background: rgba(139,92,246,.1);
        border: 1px solid rgba(139,92,246,.2);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: .9rem;
        color: #a78bfa;
        transition: all var(--transition);
    }

    .tree-card:hover .item-icon-wrap {
        background: rgba(139,92,246,.18);
        box-shadow: 0 0 15px rgba(139,92,246,.3);
    }

    /* ============================================
       ITEM INFO
    ============================================ */
    .item-info { flex: 1; min-width: 0; }

    .item-name {
        font-size: .92rem;
        font-weight: 600;
        color: var(--clr-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4;
    }

    .item-route {
        font-size: .72rem;
        color: var(--clr-text-dim);
        font-weight: 500;
        font-family: 'SF Mono', 'Fira Code', monospace;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: .1rem;
    }

    /* ============================================
       ORDER BADGE
    ============================================ */
    .order-badge {
        width: 26px; height: 26px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        color: var(--clr-text-sub);
        flex-shrink: 0;
        font-family: 'SF Mono', 'Fira Code', monospace;
        transition: all var(--transition);
    }

    .tree-card:hover .order-badge {
        background: rgba(139,92,246,.15);
        border-color: rgba(139,92,246,.35);
        color: #c4b5fd;
    }

    /* ============================================
       PARENT SELECTOR  (dropdown in row)
    ============================================ */
    .parent-select-wrap {
        flex-shrink: 0;
    }

    .parent-select {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        color: var(--clr-text-sub);
        border-radius: var(--radius-md);
        font-size: .75rem;
        font-weight: 500;
        padding: .35rem .65rem;
        cursor: pointer;
        transition: all var(--transition);
        font-family: 'Inter', sans-serif;
        outline: none;
        max-width: 140px;
    }

    .parent-select option {
        background: #1e1e2e;
        color: #e2e8f0;
    }

    .parent-select:hover, .parent-select:focus {
        background: rgba(139,92,246,.12);
        border-color: var(--clr-border-hl);
        color: var(--clr-text);
        box-shadow: 0 0 0 3px rgba(139,92,246,.15);
    }

    /* ============================================
       ACTIVE TOGGLE
    ============================================ */
    .toggle-pill {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .toggle-pill input {
        opacity: 0;
        width: 0; height: 0;
        position: absolute;
    }

    .toggle-track {
        position: absolute;
        inset: 0;
        border-radius: 999px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        transition: all var(--transition);
        cursor: pointer;
    }

    .toggle-thumb {
        position: absolute;
        top: 3px; left: 3px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: var(--clr-text-dim);
        transition: all var(--transition);
        pointer-events: none;
    }

    .toggle-pill input:checked ~ .toggle-track {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border-color: transparent;
        box-shadow: 0 0 12px rgba(139,92,246,.5);
    }

    .toggle-pill input:checked ~ .toggle-thumb {
        transform: translateX(20px);
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,.3);
    }

    /* ============================================
       ITEM ACTIONS  (small buttons on right)
    ============================================ */
    .item-actions {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-shrink: 0;
    }

    .id-chip {
        font-size: .68rem;
        font-weight: 600;
        color: var(--clr-text-dim);
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.07);
        padding: .2rem .55rem;
        border-radius: 6px;
        font-family: 'SF Mono', 'Fira Code', monospace;
        white-space: nowrap;
    }

    /* ============================================
       TOAST NOTIFICATIONS
    ============================================ */
    .toast-stack {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        pointer-events: none;
    }

    .toast-msg {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1.25rem;
        border-radius: var(--radius-lg);
        backdrop-filter: blur(24px);
        max-width: 340px;
        pointer-events: auto;
        animation: toastIn .35s cubic-bezier(.34,1.56,.64,1) forwards;
        font-size: .85rem;
        font-weight: 600;
        box-shadow: 0 10px 40px rgba(0,0,0,.5);
        position: relative;
        overflow: hidden;
    }

    .toast-msg.success {
        background: rgba(16,185,129,.15);
        border: 1px solid rgba(16,185,129,.35);
        color: #34d399;
    }

    .toast-msg.error {
        background: rgba(239,68,68,.15);
        border: 1px solid rgba(239,68,68,.35);
        color: #f87171;
    }

    .toast-msg.info {
        background: rgba(139,92,246,.15);
        border: 1px solid rgba(139,92,246,.35);
        color: #a78bfa;
    }

    .toast-msg::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        height: 2px;
        background: currentColor;
        animation: toastTimer 4s linear forwards;
    }

    .toast-msg.leaving {
        animation: toastOut .3s ease forwards;
    }

    @keyframes toastIn {
        0%   { opacity: 0; transform: translateX(60px) scale(.9); }
        100% { opacity: 1; transform: translateX(0) scale(1); }
    }

    @keyframes toastOut {
        0%   { opacity: 1; transform: translateX(0) scale(1); }
        100% { opacity: 0; transform: translateX(60px) scale(.9); }
    }

    @keyframes toastTimer {
        0%   { width: 100%; }
        100% { width: 0%; }
    }

    /* ============================================
       SAVE INDICATOR OVERLAY
    ============================================ */
    .save-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.6);
        backdrop-filter: blur(8px);
        z-index: 9990;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s ease;
    }

    .save-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .save-spinner {
        text-align: center;
    }

    .spinner-ring {
        width: 60px; height: 60px;
        border: 3px solid rgba(139,92,246,.2);
        border-top: 3px solid var(--clr-violet);
        border-radius: 50%;
        animation: spin .8s linear infinite;
        margin: 0 auto 1rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .save-spinner p {
        color: var(--clr-text-sub);
        font-size: .9rem;
        font-weight: 500;
    }

    /* ============================================
       EMPTY STATE
    ============================================ */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--clr-text-dim);
    }

    .empty-state i { font-size: 2rem; margin-bottom: .75rem; display: block; }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        main { padding: 1rem !important; }

        .sbm-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .sbm-header-left h1 { font-size: 1.5rem; }

        .sbm-actions { width: 100%; }

        .btn-sbm { flex: 1; justify-content: center; }

        .parent-select-wrap { display: none; }

        .tree-children { margin-left: 1rem; }
    }

    /* ============================================
       DRAG ZONE HIGHLIGHT (drop target feedback)
    ============================================ */
    .drop-zone-active {
        background: rgba(139,92,246,.06) !important;
        border: 2px dashed rgba(139,92,246,.4) !important;
        border-radius: var(--radius-lg);
    }

    /* ROOT add-more zone */
    .root-drop-hint {
        text-align: center;
        padding: .75rem;
        border: 2px dashed rgba(139,92,246,.15);
        border-radius: var(--radius-lg);
        color: var(--clr-text-dim);
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-top: .5rem;
        transition: all var(--transition);
    }

    .root-drop-hint.active {
        border-color: rgba(139,92,246,.5);
        background: rgba(139,92,246,.05);
        color: #a78bfa;
    }
</style>
@endpush

@section('content')

{{-- Save overlay spinner --}}
<div class="save-overlay" id="saveOverlay">
    <div class="save-spinner">
        <div class="spinner-ring"></div>
        <p>Đang lưu thay đổi…</p>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-stack" id="toastStack"></div>

{{-- ══════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════ --}}
<div class="sbm-header">
    <div class="sbm-header-left">
        <h1><i class="fas fa-layer-group me-2"></i>Sidebar Management</h1>
        <p>Kéo thả để sắp xếp thứ tự &amp; cấp bậc các mục menu</p>
    </div>
    <div class="sbm-actions">
        <button class="btn-sbm btn-reset" onclick="handleReset()">
            <i class="fas fa-undo"></i> Reset
        </button>
        <button class="btn-sbm btn-save" id="btnSave" onclick="handleSave()">
            <i class="fas fa-cloud-upload-alt"></i> Lưu thay đổi
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════════ --}}
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-icon violet"><i class="fas fa-bars"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $sidebarItems->count() }}</div>
            <div class="stat-label">Tổng mục</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $sidebarItems->where('is_active', true)->count() }}</div>
            <div class="stat-label">Đang bật</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="fas fa-eye-slash"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $sidebarItems->where('is_active', false)->count() }}</div>
            <div class="stat-label">Đã ẩn</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-sitemap"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $rootItems->count() }}</div>
            <div class="stat-label">Tabs gốc</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     HINT BOX
══════════════════════════════════════════════ --}}
<div class="hint-box">
    <div class="hint-box-icon"><i class="fas fa-magic"></i></div>
    <div class="hint-box-content">
        <h6>Hướng dẫn sử dụng</h6>
        <div class="hint-pills">
            <span class="hint-pill"><i class="fas fa-grip-vertical"></i> Kéo ☰ để đổi thứ tự</span>
            <span class="hint-pill"><i class="fas fa-arrows-alt"></i> Kéo vào/ra để đổi cha-con</span>
            <span class="hint-pill"><i class="fas fa-list"></i> Chọn dropdown để gán parent thủ công</span>
            <span class="hint-pill"><i class="fas fa-toggle-on"></i> Toggle để bật/tắt hiển thị</span>
            <span class="hint-pill"><i class="fas fa-cloud-upload-alt"></i> Nhấn "Lưu thay đổi" để áp dụng</span>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TREE PANEL
══════════════════════════════════════════════ --}}
<div class="tree-panel">
    <div class="tree-panel-header">
        <h2><i class="fas fa-project-diagram"></i> Cây menu điều hướng</h2>
        <span class="tree-panel-badge">Sortable + Nested</span>
    </div>
    <div class="tree-body">
        <div class="sortable-root" id="sortable-root">

            @forelse($rootItems->sortBy('order') as $item)
            <div class="tree-item" data-id="{{ $item->id }}" data-parent="">
                <div class="tree-card">
                    {{-- Drag handle --}}
                    <div class="drag-handle" title="Kéo để sắp xếp">
                        <i class="fas fa-grip-vertical"></i>
                    </div>

                    {{-- Order badge --}}
                    <span class="order-badge" title="Thứ tự">{{ $loop->index }}</span>

                    {{-- Icon --}}
                    <div class="item-icon-wrap">{!! $item->icon !!}</div>

                    {{-- Name / Route --}}
                    <div class="item-info">
                        <div class="item-name">{{ $item->name }}</div>
                        @if($item->route_name)
                            <div class="item-route">{{ $item->route_name }}</div>
                        @endif
                    </div>

                    {{-- Parent selector --}}
                    <div class="parent-select-wrap">
                        <select class="parent-select"
                                title="Gán tab cha"
                                onchange="handleParentChange({{ $item->id }}, this.value)">
                            <option value="" selected>— Root —</option>
                            @foreach($rootItems as $p)
                                @if($p->id !== $item->id)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Item actions --}}
                    <div class="item-actions">
                        <label class="toggle-pill" title="{{ $item->is_active ? 'Đang bật' : 'Đang tắt' }}">
                            <input type="checkbox"
                                   id="toggle-{{ $item->id }}"
                                   {{ $item->is_active ? 'checked' : '' }}
                                   onchange="handleToggle({{ $item->id }})">
                            <span class="toggle-track"></span>
                            <span class="toggle-thumb"></span>
                        </label>
                        <span class="id-chip">#{{ $item->id }}</span>
                    </div>
                </div>

                {{-- Children --}}
                @if($item->children && $item->children->count() > 0)
                <div class="tree-children sortable-children" data-parent="{{ $item->id }}">
                    @foreach($item->children->sortBy('order') as $child)
                    <div class="tree-item" data-id="{{ $child->id }}" data-parent="{{ $child->parent_id }}">
                        <div class="tree-card">
                            <div class="drag-handle" title="Kéo để sắp xếp">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            <span class="order-badge">{{ $loop->index }}</span>
                            <div class="item-icon-wrap">{!! $child->icon !!}</div>
                            <div class="item-info">
                                <div class="item-name">{{ $child->name }}</div>
                                @if($child->route_name)
                                    <div class="item-route">{{ $child->route_name }}</div>
                                @endif
                            </div>
                            <div class="parent-select-wrap">
                                <select class="parent-select"
                                        title="Gán tab cha"
                                        onchange="handleParentChange({{ $child->id }}, this.value)">
                                    <option value="">— Root —</option>
                                    @foreach($rootItems as $p)
                                        <option value="{{ $p->id }}"
                                                {{ $child->parent_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="item-actions">
                                <label class="toggle-pill">
                                    <input type="checkbox"
                                           id="toggle-{{ $child->id }}"
                                           {{ $child->is_active ? 'checked' : '' }}
                                           onchange="handleToggle({{ $child->id }})">
                                    <span class="toggle-track"></span>
                                    <span class="toggle-thumb"></span>
                                </label>
                                <span class="id-chip">#{{ $child->id }}</span>
                            </div>
                        </div>

                        {{-- Level 3: grandchildren --}}
                        @if($child->children && $child->children->count() > 0)
                        <div class="tree-children sortable-children" data-parent="{{ $child->id }}">
                            @foreach($child->children->sortBy('order') as $grandchild)
                            <div class="tree-item" data-id="{{ $grandchild->id }}" data-parent="{{ $grandchild->parent_id }}">
                                <div class="tree-card">
                                    <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                                    <span class="order-badge">{{ $loop->index }}</span>
                                    <div class="item-icon-wrap">{!! $grandchild->icon !!}</div>
                                    <div class="item-info">
                                        <div class="item-name">{{ $grandchild->name }}</div>
                                        @if($grandchild->route_name)
                                            <div class="item-route">{{ $grandchild->route_name }}</div>
                                        @endif
                                    </div>
                                    <div class="parent-select-wrap">
                                        <select class="parent-select"
                                                onchange="handleParentChange({{ $grandchild->id }}, this.value)">
                                            <option value="">— Root —</option>
                                            @foreach($rootItems as $p)
                                                <option value="{{ $p->id }}"
                                                        {{ $grandchild->parent_id == $p->id ? 'selected' : '' }}>
                                                    {{ $p->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="item-actions">
                                        <label class="toggle-pill">
                                            <input type="checkbox"
                                                   id="toggle-{{ $grandchild->id }}"
                                                   {{ $grandchild->is_active ? 'checked' : '' }}
                                                   onchange="handleToggle({{ $grandchild->id }})">
                                            <span class="toggle-track"></span>
                                            <span class="toggle-thumb"></span>
                                        </label>
                                        <span class="id-chip">#{{ $grandchild->id }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="tree-children sortable-children" data-parent="{{ $child->id }}"></div>
                        @endif

                    </div>
                    @endforeach
                </div>
                @else
                <div class="tree-children sortable-children" data-parent="{{ $item->id }}"></div>
                @endif

            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                Chưa có mục menu nào
            </div>
            @endforelse

            <div class="root-drop-hint" id="rootDropHint">
                <i class="fas fa-level-down-alt me-1"></i> Kéo vào đây để đặt ở Root Level
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

<script>
/* =============================================
   ROUTES (injected from Laravel)
============================================= */
const ROUTES = {
    order:  '{{ route("admin.sidebar.order") }}',
    parent: '{{ route("admin.sidebar.parent") }}',
    toggle: '{{ route("admin.sidebar.toggle-active") }}',
};
const CSRF = '{{ csrf_token() }}';

/* =============================================
   STATE
============================================= */
let pendingChanges = false;
let allSortables   = [];

/* =============================================
   INIT SORTABLE (recursive on all lists)
============================================= */
function initAllSortables() {
    allSortables.forEach(s => s.destroy());
    allSortables = [];

    // Root list
    const rootEl = document.getElementById('sortable-root');
    createSortable(rootEl, null);

    // All child lists
    document.querySelectorAll('.sortable-children').forEach(el => {
        const parentId = el.dataset.parent || null;
        createSortable(el, parentId);
    });
}

function createSortable(el, parentId) {
    const s = Sortable.create(el, {
        group: 'sidebar-tree',          // shared group → cross-list drag supported
        animation: 200,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'dragging-active',
        dragClass: 'sortable-drag',
        fallbackTolerance: 5,
        easing: 'cubic-bezier(0.25, 1, 0.5, 1)',

        onStart(evt) {
            // Highlight all drop zones
            document.querySelectorAll('.sortable-children, #sortable-root')
                    .forEach(z => z.classList.add('drop-zone-active'));
            document.getElementById('rootDropHint').classList.add('active');
        },

        onEnd(evt) {
            // Remove highlights
            document.querySelectorAll('.sortable-children, #sortable-root')
                    .forEach(z => z.classList.remove('drop-zone-active'));
            document.getElementById('rootDropHint').classList.remove('active');

            const itemEl   = evt.item;
            const targetList = evt.to;
            const newParentId = targetList.dataset.parent || null;
            const itemId   = parseInt(itemEl.dataset.id);
            const oldParentId = itemEl.dataset.parent || null;

            // If crossed into a different parent list → update parent immediately
            if (String(newParentId) !== String(oldParentId)) {
                itemEl.dataset.parent = newParentId || '';

                // Sync the dropdown select if present
                const sel = itemEl.querySelector('.parent-select');
                if (sel) {
                    sel.value = newParentId || '';
                }

                pushParentChange(itemId, newParentId);
            }

            updateBadgeNumbers();
            markPendingChange();
        },
    });

    allSortables.push(s);
}

/* =============================================
   UPDATE ORDER BADGE NUMBERS VISUALLY
============================================= */
function updateBadgeNumbers() {
    document.querySelectorAll('#sortable-root > .tree-item').forEach((item, i) => {
        const badge = item.querySelector(':scope > .tree-card .order-badge');
        if (badge) badge.textContent = i;

        item.querySelectorAll(':scope > .sortable-children > .tree-item').forEach((child, j) => {
            const cb = child.querySelector(':scope > .tree-card .order-badge');
            if (cb) cb.textContent = j;
        });
    });
}

/* =============================================
   PARENT CHANGE (API)
============================================= */
function handleParentChange(itemId, parentId) {
    pushParentChange(itemId, parentId || null, true);  // from dropdown → reload
}

function pushParentChange(itemId, parentId, reloadAfter = false) {
    showToast('Đang cập nhật cấu trúc…', 'info');

    fetch(ROUTES.parent, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ id: itemId, parent_id: parentId || null }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Đã cập nhật cấp bậc!', 'success');
            if (reloadAfter) {
                setTimeout(() => location.reload(), 900);
            } else {
                // Re-init sortables to capture new DOM structure
                setTimeout(initAllSortables, 300);
            }
        } else {
            showToast(data.message || 'Lỗi cập nhật parent', 'error');
        }
    })
    .catch(() => showToast('Lỗi kết nối server', 'error'));
}

/* =============================================
   SAVE ORDER
============================================= */
function handleSave() {
    const orders = collectOrders();

    document.getElementById('saveOverlay').classList.add('active');

    fetch(ROUTES.order, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ orders }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('saveOverlay').classList.remove('active');
        if (data.success) {
            showToast('Đã lưu thứ tự thành công!', 'success');
            pendingChanges = false;
            document.getElementById('btnSave').innerHTML =
                '<i class="fas fa-check"></i> Đã lưu';
            setTimeout(() => {
                document.getElementById('btnSave').innerHTML =
                    '<i class="fas fa-cloud-upload-alt"></i> Lưu thay đổi';
            }, 2500);
        } else {
            showToast(data.message || 'Lỗi lưu thứ tự', 'error');
        }
    })
    .catch(() => {
        document.getElementById('saveOverlay').classList.remove('active');
        showToast('Lỗi kết nối server', 'error');
    });
}

function collectOrders() {
    const orders = [];
    let idx = 0;

    document.querySelectorAll('#sortable-root > .tree-item').forEach(rootItem => {
        orders.push({ id: parseInt(rootItem.dataset.id), order: idx++ });

        rootItem.querySelectorAll(':scope > .sortable-children > .tree-item').forEach(child => {
            orders.push({ id: parseInt(child.dataset.id), order: idx++ });

            child.querySelectorAll(':scope > .sortable-children > .tree-item').forEach(gc => {
                orders.push({ id: parseInt(gc.dataset.id), order: idx++ });
            });
        });
    });

    return orders;
}

/* =============================================
   TOGGLE ACTIVE
============================================= */
function handleToggle(itemId) {
    fetch(ROUTES.toggle, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ id: itemId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const msg = data.is_active ? 'Đã bật hiển thị' : 'Đã ẩn mục';
            showToast(msg, 'success');
        } else {
            showToast(data.message || 'Lỗi cập nhật', 'error');
        }
    })
    .catch(() => showToast('Lỗi kết nối', 'error'));
}

/* =============================================
   RESET
============================================= */
function handleReset() {
    if (!pendingChanges && !confirm('Tải lại trang để hoàn tác tất cả thay đổi?')) return;
    location.reload();
}

/* =============================================
   PENDING CHANGE INDICATOR
============================================= */
function markPendingChange() {
    pendingChanges = true;
    const btn = document.getElementById('btnSave');
    if (!btn.classList.contains('pending')) {
        btn.classList.add('pending');
        btn.innerHTML = '<i class="fas fa-circle" style="color:#fbbf24;font-size:.5rem;vertical-align:middle;margin-right:.3rem"></i><i class="fas fa-cloud-upload-alt"></i> Lưu thay đổi';
    }
}

/* =============================================
   TOAST NOTIFICATIONS
============================================= */
function showToast(message, type = 'info') {
    const stack = document.getElementById('toastStack');
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };

    const el = document.createElement('div');
    el.className = `toast-msg ${type}`;
    el.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
    stack.appendChild(el);

    setTimeout(() => {
        el.classList.add('leaving');
        setTimeout(() => el.remove(), 350);
    }, 4000);
}

/* =============================================
   INIT
============================================= */
document.addEventListener('DOMContentLoaded', () => {
    initAllSortables();
    updateBadgeNumbers();
});

// Warn before leaving with unsaved order changes
window.addEventListener('beforeunload', e => {
    if (pendingChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>

@endsection
