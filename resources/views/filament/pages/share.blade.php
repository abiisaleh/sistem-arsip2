<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-4/6">
            <div class="flex justify-between gap-2">
                {{ $this->folderAction }}

                <div>
                    {{ $this->searchAction }}
                </div>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 mt-6 max-h-[340px] overflow-y-scrool overflow-x-hidden">
                @foreach ($this->folders as $folder)
                    <div class="focus:fw-bold cursor-pointer">
                        <button class="aspect-square w-full rounded-lg bg-gray-100 border-2 border-gray-50 focus:border-blue-600">
                            <x-filament::icon icon="heroicon-s-folder" class="h-20 w-full text-yellow-400"/>
                        </button>
                        <div class="px-2">
                            <p class="turncate text-sm font-medium text-gray-900">{{$folder}}</p>
                            <p class="turncate text-xs text-gray-500">empty</p>
                        </div>
                    </div>
                @endforeach

                @php
                    function formatBytes($bytes, $precision = 0) {
                        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                        $bytes = max($bytes, 0);
                        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                        $pow = min($pow, count($units) - 1);

                        return round($bytes / (1024 ** $pow) , $precision).' '.$units[$pow];
                    }
                @endphp

                @foreach ($this->files as $file)
                    @php
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $size = formatBytes(Illuminate\Support\Facades\Storage::drive('public')->size($file));
                    @endphp
                    <div class="focus:fw-bold cursor-pointer">
                        <button class="aspect-square w-full rounded-lg bg-gray-100 border-2 border-gray-50 focus:border-blue-600">
                            <x-filament::icon icon="bi-filetype-{{$extension}}" class="h-20 w-full text-red-400"/>
                        </button>
                        <div class="px-2">
                            <p class="turncate text-sm font-medium text-gray-900">{{ Str::limit($file, 12) }}</p>
                            <p class="turncate text-xs text-gray-500">{{$size ?? 0}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="w-full lg:w-2/6 lg:ml-6 mt-6 lg:mt-0 bg-white rounded-lg shadow-sm p-4 flex flex-col gap-2">
            <div class="bg-gray-100 rounded-lg h-[140px] w-full">

            </div>

            <div>
                <p class="turncate font-medium text-gray-900">{{$folder}}</p>
                <p class="turncate text-xs text-gray-500">empty</p>
            </div>

            <div class="text-xs">
                <p class="turncate font-medium text-gray-900 py-2 border-b-[1px]">Detail</p>
                <div class="flex justify-between py-2 border-b-[1px]">
                    <p class="text-gray-500">Jenis</p>
                    <p class="font-medium text-gray-900">Gambar</p>
                </div>
                <div class="flex justify-between py-2 border-b-[1px]">
                    <p class="text-gray-500">Pemilik</p>
                    <p class="font-medium text-gray-900">abiisaleh</p>
                </div>
                <div class="flex justify-between py-2 border-b-[1px]">
                    <p class="text-gray-500">Diupload</p>
                    <p class="font-medium text-gray-900">17 Agustus 2025</p>
                </div>
            </div>

            <div class="flex gap-2">
                {{$this->downloadAction}} {{$this->viewAction}}
            </div>

        </div>

    </div>
</x-filament-panels::page>
