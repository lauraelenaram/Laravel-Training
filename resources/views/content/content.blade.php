    @extends('principal')
    @section('content')  
        <template v-if="menu==0">
            <h1>Contenido del menu 0</h1>
        </template>  

        <template v-if="menu==1">
            <category-component></category-component>
        </template>

        <template v-if="menu==2">
        <article-component></article-component>
        </template>

        <template v-if="menu==3">
            <h1>Contenido del menu 3</h1>
        </template>

        <template v-if="menu==4">
            <supplier-component></supplier-component>
        </template>

        <template v-if="menu==5">
            <h1>Contenido del menu 5</h1>
        </template>

        <template v-if="menu==6">
            <client-component></client-component>
        </template>

        <template v-if="menu==7">
            <h1>Contenido del menu 7</h1>
        </template>

        <template v-if="menu==8">
            <rol-component></rol-component>
        </template>

        <template v-if="menu==9">
            <h1>Contenido del menu 9</h1>
        </template>

        <template v-if="menu==10">
            <h1>Contenido del menu 10</h1>
        </template>

        <template v-if="menu==11">
            <h1>Contenido del menu 11</h1>
        </template>

        <template v-if="menu==12">
            <h1>Contenido del menu 12</h1>
        </template>
        
    @endsection