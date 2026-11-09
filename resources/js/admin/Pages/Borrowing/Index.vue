<script setup>
import LoanLedger from '../../Components/LoanLedger.vue';

defineProps({ loans: Object, filters: Object, summary: Object, people: Array });
</script>

<template>
    <LoanLedger kind="borrowing" :loans="loans" :filters="filters" :summary="summary" :people="people" />
</template>
