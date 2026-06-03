<div class="space-y-8 animate-fade-in">
    <?php echo $__env->make('site.pages.partials.oer-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- New Navigation Buttons at the beginning -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="#intro" class="flex-1 min-w-[150px] py-3 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-[11px] rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
            <?php echo e(__('Giới thiệu TNGDM')); ?>

        </a>
        <a href="#license" class="flex-1 min-w-[150px] py-3 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-[11px] rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
            <?php echo e(__('Giấy phép truy cập mở')); ?>

        </a>
        <a href="#support" class="flex-1 min-w-[150px] py-3 bg-[#7B0000] text-white text-center font-black uppercase tracking-widest text-[11px] rounded-sm hover:bg-[#5A0000] transition-colors shadow-md">
            <?php echo e(__('Hỗ trợ tìm kiếm')); ?>

        </a>
    </div>

    <h1 id="intro" class="text-2xl md:text-3xl font-black text-vttu-dark tracking-tight mb-6 uppercase">
        <?php echo e(__('Tài nguyên giáo dục mở')); ?>

    </h1>

    <div class="prose prose-sm max-w-none space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
            <p class="text-slate-600 leading-relaxed italic">
                <?php echo e(__('Tài nguyên giáo dục mở (OER) là nguồn tài nguyên truy cập miễn phí hỗ trợ cho việc học tập, nghiên cứu và giảng dạy thuộc mọi đối tượng, hướng đến một xã hội học tập suốt đời. OER cung cấp đa dạng các loại hình tài nguyên, như: Giáo trình số, tạp chí truy cập mở, bản ghi âm (recording), các khóa tập huấn miễn phí (free learning course), dữ liệu nghiên cứu (dataset), video... OER không chỉ cung cấp tài nguyên để sử dụng, mà tại đó, người dùng có thể tài sử dụng, chỉnh sửa tùy theo mục đích và giấy phép truy cập mở.')); ?>

            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
            <h2 class="text-xl font-black text-vttu-red mb-4 uppercase tracking-tight border-b pb-2">
                <?php echo e(__('Những lợi ích mang lại')); ?>

            </h2>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-slate-600 list-none p-0 m-0">
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="edit-3" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Khả năng tùy chỉnh')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('OER thuộc giấy phép CC-SA người sử dụng dùng nguồn tài nguyên có thể tùy chỉnh tài liệu.')); ?></span>
                    </div>
                </li>
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="refresh-cw" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Tính cập nhật nhanh chóng')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('OER được cập nhật nhanh chóng theo thời gian, tiếp nhận tri thức mới.')); ?></span>
                    </div>
                </li>
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="globe" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Đa dạng văn hóa')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('Phản ánh các sắc màu văn hóa khác nhau của từng quốc gia.')); ?></span>
                    </div>
                </li>
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="unlock" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Dễ dàng tiếp cận')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('Hỗ trợ tài nguyên có kinh phí thấp, tiếp cận nhanh chóng.')); ?></span>
                    </div>
                </li>
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="clock" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Truy cập thuận tiện')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('Nguồn tài nguyên số, truy cập khắp mọi nơi và mọi lúc.')); ?></span>
                    </div>
                </li>
                <li class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-vttu-red/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="scale" class="w-4 h-4 text-vttu-red"></i>
                    </div>
                    <div>
                        <strong class="text-vttu-dark block mb-0.5 uppercase tracking-wider text-[11px]"><?php echo e(__('Tính công bằng')); ?></strong>
                        <span class="text-[12px] leading-snug"><?php echo e(__('Hòa hợp giữa các nguồn tài nguyên, không phân biệt đối tượng.')); ?></span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- YouTube Video Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
            <h2 class="text-xl font-black text-vttu-red mb-4 uppercase tracking-tight">
                <?php echo e(__('Video giới thiệu tài nguyên giáo dục mở')); ?>

            </h2>
            <div class="aspect-video rounded-lg overflow-hidden shadow-inner bg-slate-100 border border-slate-200">
                <iframe class="w-full h-full" 
                        src="https://www.youtube.com/embed/ZFeyCc6we-s" 
                        title="Video giới thiệu tài nguyên giáo dục mở" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen></iframe>
            </div>
        </div>

                <div id="license" class="bg-white rounded-xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight border-b pb-2">
                        <?php echo e(__('Nguyên tắc sử dụng nguồn tài nguyên giáo dục mở')); ?>

                    </h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        <?php echo e(__('Trong phạm vi của Giấy phép Creative Common (CC), có 5 yếu tố quan trọng khi sử dụng OER:')); ?>

                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-vttu-red/30 transition-colors">
                            <strong class="text-vttu-dark block mb-1 text-sm uppercase tracking-wide"><?php echo e(__('Tái sử dụng (Reuse)')); ?></strong>
                            <p class="text-xs text-slate-600 m-0"><?php echo e(__('Có thể sử dụng lại một phần hoặc toàn bộ tác phẩm gốc;')); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-vttu-red/30 transition-colors">
                            <strong class="text-vttu-dark block mb-1 text-sm uppercase tracking-wide"><?php echo e(__('Giữ lại (Retain)')); ?></strong>
                            <p class="text-xs text-slate-600 m-0"><?php echo e(__('Được phép lưu trữ tác phẩm;')); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-vttu-red/30 transition-colors">
                            <strong class="text-vttu-dark block mb-1 text-sm uppercase tracking-wide"><?php echo e(__('Biên tập lại (Revise)')); ?></strong>
                            <p class="text-xs text-slate-600 m-0"><?php echo e(__('Có thể hiệu chỉnh, sửa chữa hoặc thay thế một phần nội dung tác phẩm phù hợp với nhu cầu;')); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-vttu-red/30 transition-colors">
                            <strong class="text-vttu-dark block mb-1 text-sm uppercase tracking-wide"><?php echo e(__('Pha trộn (Remix)')); ?></strong>
                            <p class="text-xs text-slate-600 m-0"><?php echo e(__('Kết hợp nội dung gốc của tác phẩm với các nội dung phái sinh khác để tạo ra tác phẩm mới;')); ?></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-vttu-red/30 transition-colors">
                            <strong class="text-vttu-dark block mb-1 text-sm uppercase tracking-wide"><?php echo e(__('Phân phối lại (Redistribute)')); ?></strong>
                            <p class="text-xs text-slate-600 m-0"><?php echo e(__('Được phép sao chép, chia sẻ nội dung tác phẩm với bất kỳ ai, theo bất cứ hình thức nào.')); ?></p>
                        </div>
                    </div>

                    <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight border-b pb-2">
                        <?php echo e(__('Các loại giấy phép xuất bản mở')); ?>

                    </h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        <?php echo e(__('Nguồn TNGDM thường được cấp phép mở theo Giấy phép Creative Commons (CC). Có 6 loại giấy phép theo dạng này:')); ?>

                    </p>
                    
                    <div class="space-y-6">
                        <!-- CC BY -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by/4.0/88x31.png" class="w-32 h-auto" alt="CC BY">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Đây là yêu cầu bắt buộc trong tất cả các loại giấy phép CC. Giấy phép loại này cho phép người sử dụng sao chép, chỉnh sửa, pha trộn và chia sẻ nội dung tác phẩm gốc cho cả mục đích phi lợi nhuận lẫn thương mại, miễn là phải thừa nhận sự ghi công theo yêu cầu của tác giả.')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- CC BY-SA -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by-sa/4.0/88x31.png" class="w-32 h-auto" alt="CC BY-SA">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY) - Chia sẻ tương tự (SA)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Bao gồm tất cả các quyền như Giấy phép Ghi công nhưng yêu cầu tác phẩm phái sinh phải được cấp phép theo những điều kiện giống như trong tác phẩm gốc.')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- CC BY-ND -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by-nd/4.0/88x31.png" class="w-32 h-auto" alt="CC BY-ND">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY) - Không phái sinh (ND)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Chỉ cho phép sao chụp, chia sẻ tác phẩm ở dạng nguyên vẹn (không được thay đổi hình thức và nội dung tác phẩm gốc) cho mục đích phi lợi nhuận lẫn thương mại.')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- CC BY-NC -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by-nc/4.0/88x31.png" class="w-32 h-auto" alt="CC BY-NC">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY) - Phi thương mại (NC)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Chỉ cho phép người sử dụng sao chép, chỉnh sửa, pha trộn và chia sẻ nội dung tác phẩm gốc cho mục đích phi lợi.')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- CC BY-NC-SA -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by-nc-sa/4.0/88x31.png" class="w-32 h-auto" alt="CC BY-NC-SA">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY) - Phi thương mại (NC) - Chia sẻ tương tự (SA)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Bao gồm tất cả các quyền như Giấy phép Ghi công (BY) - Chia sẻ tương tự (SA) nhưng giới hạn chỉ được sử dụng tác phẩm cho mục đích phi lợi nhuận.')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- CC BY-NC-ND -->
                        <div class="flex flex-col md:flex-row gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center justify-center bg-white p-4 rounded-lg shadow-sm">
                                <img src="https://licensebuttons.net/l/by-nc-nd/4.0/88x31.png" class="w-32 h-auto" alt="CC BY-NC-ND">
                            </div>
                            <div>
                                <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Ghi công (BY) - Phi thương mại (NC) - Không phái sinh (ND)')); ?></h3>
                                <p class="text-xs text-slate-600 leading-relaxed m-0">
                                    <?php echo e(__('Bao gồm tất cả các quyền như Giấy phép Ghi công (BY) - Không phái sinh (ND) nhưng giới hạn chỉ được sử dụng tác phẩm cho mục đích phi lợi nhuận.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <p class="text-sm text-slate-600">
                            <?php echo e(__('Xem thêm tại trang sau:')); ?> 
                            <a href="https://creativecommons.org/licenses/" target="_blank" class="text-vttu-red font-bold hover:underline">https://creativecommons.org/licenses/</a>
                        </p>
                    </div>
                </div>

                <!-- Additional Info Video -->
                <div class="bg-white rounded-xl p-8 shadow-lg border border-slate-100">
                    <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight">
                        <?php echo e(__('Thông tin thêm về Giấy phép xuất bản mở')); ?>

                    </h2>
                    <div class="aspect-video rounded-lg overflow-hidden shadow-inner bg-slate-100 border border-slate-200">
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/HKfqoPYJdVc" 
                                title="Thông tin thêm về Giấy phép xuất bản mở" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div id="support" class="space-y-6 mt-12">
                <h2 class="text-2xl font-black text-vttu-red mb-6 uppercase tracking-tight border-b pb-2">
                    <?php echo e(__('Hỗ trợ tìm kiếm')); ?>

                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Google Research -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cơ sở dữ liệu Google Research')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('Google Research là cơ sở dữ liệu tập hợp các nghiên cứu và liên kết nghiên cứu về khoa học dữ liệu, khoa học máy tính với các chuyên gia trên thế giới.')); ?>

                        </p>
                        <a href="https://research.google/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- Internet Archive -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Thư viện số Internet Archive')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('Internet Archive, một tổ chức phi lợi nhuận, đang xây dựng một thư viện kỹ thuật số gồm các trang Internet và các hiện vật văn hóa khác ở dạng kỹ thuật số.')); ?>

                        </p>
                        <a href="https://archive.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- Project Gutenberg -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Dự án Gutenberg')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('Dự án Gutenberg là một thư viện gồm hơn 70.000 sách điện tử miễn phí, những tác phẩm văn học của thế giới, tập trung vào những tác phẩm cũ đã hết hạn bản quyền của Hoa Kỳ.')); ?>

                        </p>
                        <a href="https://www.gutenberg.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- DOAB -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cơ sở dữ liệu DOAB')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('DOAB là một cơ sở hạ tầng mở cam kết với khoa học mở, các nhà xuất bản hàn lâm đã được mời cung cấp siêu dữ liệu về sách truy cập mở của họ cho DOAB.')); ?>

                        </p>
                        <a href="https://www.doabooks.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- OATD -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cơ sở dữ liệu OATD')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('Siêu dữ liệu (thông tin về các luận án) của OATD đến từ hơn 1100 trường cao đẳng, đại học và viện nghiên cứu, hiện đang lập chỉ mục cho 6.555.718 luận án.')); ?>

                        </p>
                        <a href="https://oatd.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- DOAJ -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cơ sở dữ liệu tạp chí mở DOAJ')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('DOAJ được thành lập vào năm 2003 với 300 nhan đề tạp chí truy cập mở. Cho đến hiện tại, cơ sở dữ liệu đã thu thập khoảng 17 500 tạp chí peer-review.')); ?>

                        </p>
                        <a href="https://doaj.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- arXiv -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cơ sở dữ liệu arXiv')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('arXiv là một nền tảng chia sẻ nghiên cứu, hiện lưu trữ hơn hai triệu bài báo học thuật trong tám lĩnh vực chủ đề, được tuyển chọn bởi cộng đồng khoa học.')); ?>

                        </p>
                        <a href="https://arxiv.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- DART-Europe -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Cổng thông tin LV/LA Châu Âu')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('DART-Europe được thành lập vào năm 2005 dưới sự hợp tác của các thư viện đại học, hỗ trợ truy cập toàn văn đến các luận văn/luận án khắp châu Âu.')); ?>

                        </p>
                        <a href="https://www.dart-europe.org/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- Open Textbook Library -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('Open Textbook Library')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('Các sách giáo khoa trong Thư viện sách giáo khoa mở được coi là mở vì chúng được sử dụng và phân phối miễn phí, đồng thời được cấp phép để tự do điều chỉnh.')); ?>

                        </p>
                        <a href="https://open.umn.edu/opentextbooks/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>

                    <!-- Moet Luan Van -->
                    <div class="bg-white rounded-xl p-6 shadow-md border border-slate-100 flex flex-col hover:shadow-lg transition-shadow">
                        <h3 class="font-black text-vttu-dark mb-2 uppercase tracking-wide text-sm"><?php echo e(__('CSDL luận văn - luận án thuộc bộ GD-ĐT')); ?></h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 flex-1">
                            <?php echo e(__('CSDL luận văn - luận án bộ GD-ĐT là một cơ sở dữ liệu tập trung những luận văn, luận án từ nhiều nguồn nghiên cứu khác nhau.')); ?>

                        </p>
                        <a href="http://luanvan.moet.edu.vn/" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-vttu-red text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-vttu-red hover:text-white transition-all">
                            <?php echo e(__('Truy cập')); ?> <i data-lucide="external-link" class="w-3 h-3 ml-1.5"></i>
                        </a>
                    </div>
                </div>

                <div class="mt-12 text-center pt-8 border-t border-slate-100">
                    <a href="<?php echo e(route('site.page', 'tai-nguyen-giao-duc-mo')); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-vttu-red text-white rounded-xl hover:bg-vttu-dark active:scale-[0.97] transition-all shadow-lg shadow-vttu-red/20 font-black uppercase tracking-wider text-sm">
                        <span><?php echo e(__('Khám phá kho tài liệu OER của VTTU')); ?></span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
</div>
<?php /**PATH E:\Workspace\VTTU\Laravel\VTTLib\resources\views/site/pages/partials/oer-intro-content.blade.php ENDPATH**/ ?>