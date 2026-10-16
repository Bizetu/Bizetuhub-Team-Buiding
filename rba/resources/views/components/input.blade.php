@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-200 border border-transparent rounded-lg text-lg text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1F3B67] focus:border-transparent transition focus:border-[#1F3B67] focus:ring-[#1F3B67]']) }}>
