<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
<body class="rounded-full bg-radial-[at_50%_50%] from-zinc-900 to-white dark:from-white dark:to-[#151611] to-75%">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]  rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none">
                    <a href="{{ route('login') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-30 w-30 mb-1 items-center justify-center rounded-md hidden dark:flex">
                        <x-dark-app-logo-icon class="size-100 text-black dark:text-white" />
                    </span>
                    <span class="flex h-30 w-30 mb-1 items-center justify-center rounded-md dark:hidden">
                        <x-app-logo-icon class="size-100 text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
                </div>
                <div class="bg-gradient-to-r from-white to-zinc-900  dark:bg-gradient-to-r dark:from-[#151611] dark:to-white relative lg:-ms-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg! aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden ">
                    <img
                        src="{{ asset('image/car.png') }}"
                        alt="car Image"
                        class="w-120 mx-auto mt-30 object-contain"
                        loading="lazy"
                    >
            </main>
        </div>
        @fluxScripts
    </body>
</html>