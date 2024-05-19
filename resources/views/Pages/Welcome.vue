<script>
import Nav from "../Shared/Nav.vue";

export default {
    components: {
        Nav
    }
}
</script>

<template>
    <main>
        <header>
            <Nav/>
        </header>

        <h1 class="heading">Home Page</h1>
        <article>
            <slot />
        </article>
    </main>
</template>
