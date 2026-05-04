<div id="medical-container" class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in fade-in duration-500">
    @forelse($medicalResources as $res)
    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 hover:border-vttu-red/20 hover:bg-vttu-red/5 transition-all group cursor-pointer">
        <div class="w-12 h-12 bg-vttu-red/10 rounded-2xl flex items-center justify-center text-vttu-red mb-4 group-hover:bg-vttu-red group-hover:text-white transition-all">
            <i class="fas fa-file-medical"></i>
        </div>
        <h4 class="font-black text-vttu-dark line-clamp-2 group-hover:text-vttu-red">{{ $res->title }}</h4>
    </div>
    @empty
        <div class="col-span-3 py-12 text-center text-slate-400 font-medium">
            {{ __('Hiện chưa có tài liệu trong chuyên đề này.') }}
        </div>
    @endforelse
</div>
