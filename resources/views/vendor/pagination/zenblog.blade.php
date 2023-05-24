@if ($paginator->hasPages())    
      
        @if (!$paginator->onFirstPage())
          <a class="prev" href="{{ $paginator->previousPageUrl() }}">Previous</a>      
        @endif

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                 <span class="page-link">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())                        
                        <a class="active">{{ $page }}</a>
                    @else                        
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
      
        @if ($paginator->hasMorePages())
          <a class="next" href="{{ $paginator->nextPageUrl() }}">Next</a>       
        @endif   

@endif