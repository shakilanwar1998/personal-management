<script>
import {Link} from "@inertiajs/vue3";
export default {
    components: {Link}
};
</script>

<template>
    <nav>
        <ul>
            <li><Link href="/">Home</Link></li>
            <li><Link href="/about">About</Link></li>
            <li><Link href="/contact">Contact</Link></li>
        </ul>
    </nav>
</template>
