<!-- Features Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($item->getDataAttribute('title'))
            <h2 class="text-3xl font-bold text-center mb-12">{{ $item->getDataAttribute('title') }}</h2>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = $item->getDataAttribute('features', []);
                $defaultFeatures = [
                    ['icon' => 'fas fa-book', 'title' => 'Tài nguyên phong phú', 'description' => 'Hàng ngàn tài liệu học tập và nghiên cứu'],
                    ['icon' => 'fas fa-users', 'title' => 'Cộng đồng học tập', 'description' => 'Kết nối với hàng ngàn sinh viên và giảng viên'],
                    ['icon' => 'fas fa-laptop', 'title' => 'Học trực tuyến', 'description' => 'Tiếp cận kiến thức mọi lúc mọi nơi']
                ];
                $displayFeatures = !empty($features) ? $features : $defaultFeatures;
            @endphp
            
            @foreach($displayFeatures as $feature)
                <div class="text-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
                    @if($feature['icon'])
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="{{ $feature['icon'] }} text-blue-600 text-2xl"></i>
                        </div>
                    @endif
                    @if($feature['title'])
                        <h3 class="text-xl font-semibold mb-2">{{ $feature['title'] }}</h3>
                    @endif
                    @if($feature['description'])
                        <p class="text-gray-600">{{ $feature['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
