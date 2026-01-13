@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Metadata Biên mục</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form thêm Metadata -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Thêm Metadata mới</h3>
                <form action="{{ route('admin.metadata.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code (VD: 41)</label>
                        <input type="text" name="metadata_code" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên Biểu ghi (VD: Mã ngôn ngữ)</label>
                        <input type="text" name="metadata_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                        <textarea name="description"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_multiple" value="1"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Cho phép chọn nhiều giá trị</label>
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Thêm
                        Metadata</button>
                </form>
            </div>

            <!-- Form thêm Metadata Value -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Thêm Giá trị (Value)</h3>
                <form action="{{ route('admin.metadata-values.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Chọn Metadata</label>
                        <select name="metadata_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            <option value="">-- Chọn Metadata --</option>
                            @foreach($metadata as $meta)
                                <option value="{{ $meta->id }}">{{ $meta->metadata_code }} - {{ $meta->metadata_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mã giá trị (VD: vie)</label>
                        <input type="text" name="value_code" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên giá trị (VD: Tiếng Việt)</label>
                        <input type="text" name="value_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                    <button type="submit"
                        class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">Thêm
                        Giá trị</button>
                </form>
            </div>
        </div>

        <!-- Danh sách Metadata -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metadata
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị
                            (Values)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($metadata as $meta)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-indigo-600">{{ $meta->metadata_code }}</div>
                                <div class="text-sm text-gray-900 font-medium">{{ $meta->metadata_name }}</div>
                                <div class="text-xs text-gray-400">{{ $meta->description }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($meta->values as $val)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200 group">
                                            {{ $val->value_name }} ({{ $val->value_code }})
                                            <form action="{{ route('admin.metadata-values.destroy', $val->id) }}" method="POST"
                                                class="ml-1 inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-indigo-400 hover:text-red-500 focus:outline-none">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.metadata.destroy', $meta->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Xóa toàn bộ metadata và các giá trị liên quan?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection