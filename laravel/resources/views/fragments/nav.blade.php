<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Letscode.hu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                @foreach($model->getMenu()->getContent() as $menuItem)
                <li class="nav-item">
                    <a class="nav-link" href="{{$menuItem->getUrl()}}">{{$menuItem->getTitle()}}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>