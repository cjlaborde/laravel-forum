<template>
    <ais-instant-search
        index-name="instant_search"
        :search-client="searchClient"
    >
        <!-- Widgets -->
        <ais-hits :escape-HTML="true">
            <div slot="item" slot-scope="{ item }">
                <h2>
                    <ais-highlight
                        attribute="name"
                        :hit="item"
                        highlightedTagName="mark"
                    />
                </h2>
                <p>
                    <ais-snippet
                        attribute="description"
                        :hit="item"
                        highlightedTagName="mark"
                    />
                </p>
            </div>
        </ais-hits>
        <ais-configure
            :attributesToSnippet="['description']"
            snippetEllipsisText="[…]"
        />

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
