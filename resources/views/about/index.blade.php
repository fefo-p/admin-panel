<x-ap-content :title="$title ?? null" :description="$description ?? null" :action="$action ?? null">
    {{--@dump($about)--}}
    <div class="font-mono text-xs dark:text-gray-400">
        {{--{!! nl2br($about) !!}--}}
        @php
            $about_array = explode("\n", $about);
            // dump($about_array);
        @endphp

        <div class="grid grid-cols-12 gap-x-16">
            @php
                foreach($about_array as $key => $line) {
                    $string = Str::of($line);

                    if ($string->isEmpty()) {
                        unset($about_array[$key]);
                    }

                    /*if ($string->endsWith('..')) {
                        $about_array[$key] = '<br>'.$line;
                    }*/

                    if (array_key_exists($key,$about_array)) {
                        echo('<div class="col-span-1"></div>');
                        echo('<div class="col-span-11">'.$line.'</div>');
                    }
                    else {
                        echo('<div class="col-span-12"><br></div>');
                    }
                }

            @endphp

        </div>
        {{--@dump($about_array)--}}

    </div>
</x-ap-content>
