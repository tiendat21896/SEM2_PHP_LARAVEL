<div class="sidebar__item">
    <ul>
        @foreach(\App\Category::all() as $cat)
            <li><a href="{{$cat->getCategoryUrl()}}">{{$cat->__get("category_name")}}</a></li>
        @endforeach
    </ul>
</div>
