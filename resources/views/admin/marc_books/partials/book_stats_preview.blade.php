@if(empty($storageLocations) || $storageLocations->isEmpty())
<div class="flex flex-col items-center justify-center py-16 text-muted-foreground">
    <i data-lucide="inbox" class="w-10 h-10 mb-2 stroke-[1.5] text-muted-foreground/50"></i>
    <p class="text-xs font-semibold">{{ __('Không tìm thấy dữ liệu vị trí kho.') }}</p>
</div>
@else
<div class="p-6 bg-card text-foreground space-y-6">
    <div class="text-center space-y-1 mb-6">
        <h1 class="text-lg font-bold tracking-tight uppercase text-foreground">
            {{ __('THỐNG KÊ SỐ LƯỢNG ĐẦU SÁCH TRONG THƯ VIỆN') }}
        </h1>
        <p class="text-xs text-muted-foreground">
            {{ __('Tổng số bản ghi thỏa điều kiện lọc: ') }} <span class="font-bold text-foreground">{{ number_format($totalCount) }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($storageLocations as $loc)
            @php
                $locCounts = $counts[$loc->id] ?? [];
                $totalLocSum = array_sum($locCounts);
            @endphp
            
            <!-- Section for each Storage Location -->
            <div class="border border-border rounded-md shadow-sm overflow-hidden bg-background">
                <!-- Storage Location Header -->
                <div class="bg-muted/40 px-4 py-2.5 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[10px] font-bold bg-primary/10 text-primary uppercase tracking-widest">
                            {{ $loc->code }}
                        </span>
                        <h3 class="text-xs font-bold text-foreground">
                            {{ $loc->name }}
                        </h3>
                        @if($loc->branch)
                            <span class="text-[11px] text-muted-foreground">({{ $loc->branch->name }})</span>
                        @endif
                    </div>
                    <div class="text-xs font-bold text-muted-foreground">
                        {{ __('Tổng số: ') }} <span class="text-sm font-black text-foreground">{{ number_format($totalLocSum) }}</span>
                    </div>
                </div>

                <!-- Table of Document Types -->
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border bg-muted/10 text-xs font-bold text-muted-foreground tracking-wider">
                            <th class="px-4 py-2 w-16 text-center border-r border-border">{{ __('STT') }}</th>
                            <th class="px-4 py-2 border-r border-border">{{ __('THỂ LOẠI TÀI LIỆU') }}</th>
                            <th class="px-4 py-2 w-32 text-center">{{ __('SỐ LƯỢNG') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border text-xs">
                        @php $stt = 1; @endphp
                        @foreach($documentTypes as $dt)
                            @php
                                $qty = $locCounts[$dt->id] ?? 0;
                            @endphp
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-4 py-2 text-center border-r border-border font-medium text-muted-foreground">{{ $stt++ }}</td>
                                <td class="px-4 py-2 border-r border-border font-semibold text-foreground">{{ $dt->name }}</td>
                                <td class="px-4 py-2 text-center font-bold text-foreground">{{ number_format($qty) }}</td>
                            </tr>
                        @endforeach
                        <!-- Total Row -->
                        <tr class="bg-muted/15 font-bold border-t border-border">
                            <td colspan="2" class="px-4 py-2.5 text-right border-r border-border uppercase tracking-wider text-muted-foreground text-[10px]">{{ __('Tổng số:') }}</td>
                            <td class="px-4 py-2.5 text-center text-sm font-black text-primary">{{ number_format($totalLocSum) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
@endif
