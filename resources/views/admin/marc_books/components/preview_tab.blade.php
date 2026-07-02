<div class="bg-card text-foreground rounded-md border border-border shadow-sm overflow-hidden">
    <div class="bg-muted px-4 py-2 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 border-b border-border">
        <div class="flex items-center gap-2">
            <span class="bg-primary/10 text-primary border border-primary/20 text-[9px] font-bold px-1.5 py-0.5 rounded-sm uppercase tracking-wider">{{ __('Xem trước MARC') }}</span>
            <h3 class="font-bold text-xs leading-none text-foreground">{{ __('Mẫu biên mục MARC21') }}</h3>
        </div>
        <span class="font-mono text-primary text-xs tracking-widest opacity-80" x-text="leader"></span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-muted/50 border-b border-border text-muted-foreground uppercase font-bold text-[10px] tracking-wider">
                    <th class="py-2 px-3 w-64">{{ __('Nhãn') }}</th>
                    <th class="py-2 px-3 text-center w-24">{{ __('Chỉ thị') }}</th>
                    <th class="py-2 px-3">{{ __('Dữ liệu nội dung') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border font-mono text-xs">
                <tr class="table-row-hover">
                    <td class="py-2 px-3 whitespace-nowrap align-top">
                        <div class="flex items-baseline gap-2">
                            <span class="text-primary font-bold tracking-tight">000</span>
                            <span class="text-[9px] text-muted-foreground uppercase font-sans tracking-tight leading-tight">{{ __('Leader') }}</span>
                        </div>
                    </td>
                    <td class="py-2 px-3 text-center align-top text-muted-foreground/60 font-mono">
                        ##
                    </td>
                    <td class="py-2 px-3 break-words text-foreground">
                        <span x-text="leader"></span>
                    </td>
                </tr>

                <template x-for="field in getActiveFields()" :key="field.tag">
                    <tr class="table-row-hover">
                        <td class="py-2 px-3 whitespace-nowrap align-top">
                            <div class="flex items-baseline gap-2">
                                <span class="text-primary font-bold tracking-tight" x-text="field.tag"></span>
                                <span class="text-[9px] text-muted-foreground uppercase font-sans tracking-tight leading-tight" x-text="field.label"></span>
                            </div>
                        </td>
                        <td class="py-2 px-3 text-center align-top text-muted-foreground/60 font-mono" x-text="(field.ind1?.trim() || '#') + (field.ind2?.trim() || '#')">
                        </td>
                        <td class="py-2 px-3">
                            <div class="flex flex-col space-y-1.5">
                                <template x-for="(sub, subIdx) in field.subfields.filter(s => s.code || s.value)" :key="subIdx">
                                    <div class="flex items-start gap-2">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0 font-mono" x-text="'$' + (sub.code || '?')"></span>
                                        <span class="text-foreground grow break-words min-w-0 leading-relaxed" x-text="sub.value || '...'"></span>
                                        <span class="shrink-0 bg-muted text-muted-foreground text-[8px] px-1.5 py-0.5 rounded-sm font-sans uppercase tracking-widest mt-0.5 border border-border"
                                            x-show="sub.code && getSubfieldLabel(field.tag, sub.code)"
                                            x-text="'[' + getSubfieldLabel(field.tag, sub.code) + ']'"></span>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
