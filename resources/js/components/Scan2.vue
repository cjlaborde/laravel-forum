<template>
    <ais-instant-search
        index-name="instant_search"
        :search-client="searchClient"
    >
        <ais-search-box>
            <div slot-scope="{ currentRefinement, isSearchStalled, refine }">
                <input
                    type="search"
                    v-model="currentRefinement"
                    @input="refine($event.currentTarget.value)"
                >
                <span :hidden="!isSearchStalled">Loading...</span>
            </div>
        </ais-search-box>

        <!-- Widgets -->
    </ais-instant-search>
</template>

<script>
    export default {
        data() {
            return {
                searchClient: algoliasearch(
                    'latency',
                    '6be0576ff61c053d5f9a3225e2a90f76'
                ),
            };
        },
    };
</script>
