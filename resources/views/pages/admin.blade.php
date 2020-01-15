@include("site-templates.admin.desktop.header")

<div id="app">
    <section class="hero is-info">
        <div class="hero-body">
            <div class="container">
                <div class="title">Супер админка</div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-3">
                    <div class="panel">
                        <v-left-nav></v-left-nav>
                    </div>
                </div>
                <div class="column is-9">
                    <nav class="breadcrumb" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="#">Bulma</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Components</a></li>
                            <li class="is-active"><a href="#" aria-current="page">Breadcrumb</a></li>
                        </ul>
                    </nav>
                    <div class="message is-link">
                        <div class="message-body">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include("site-templates.admin.desktop.footer")