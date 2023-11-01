

    <div class="content">
        @foreach ($items as $item)
        <div class="product">
            <h3>Product: {{ $item->product }}</h3>
            <p>Category: {{ $item->category }}</p>
            <p>Brand: {{ $item->brand }}</p>
        </div>
        @endforeach
        {{-- <div class="custom-pagination">
            {{ $items->links('vendor.pagination.default', ['paginator' => $items]) }}
        </div> --}}

    </div>


