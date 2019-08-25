<template>
    <ais-index
        app-id="ALGOLIA_APP_ID"
        api-key="ALGOLIA_KEY"
        index-name="threads"
        :query-parameters="{
      hitsPerPage: 3,
      attributesToSnippet: ['title', 'body'],
      snippetEllipsisText: ' [...]'
    }"
    >
        <main class="search-container">
            <div class="right-panel">
                <div id="hits">
                    <!-- Uncomment the following widget to add hits list -->
                    <ais-results class="ais-hits">
                      <div slot-scope="{ result }">
                        <hit :result="result"/>
                      </div>
                    </ais-results>
                    <ais-no-results>
                      <div slot-scope="props">
                        No results found for <strong>{{ props.query }}</strong>.
                      </div>
                    </ais-no-results>
                </div>
                <div id="searchbox">
                    <!-- Uncomment the following widget to add a search bar  -->
                     <ais-search-box placeholder="Search articles" class="ais-search-box"/>
                </div>
                <div id="stats">
                    <!-- Uncomment the following widget to add search stats -->
                    <div>
                      <ais-stats>
                        <div slot-scope="{ totalResults, processingTime, query, resultStart, resultEnd }">
                          <span role="img" aria-label="emoji">⚡️</span> <strong>{{ totalResults }}</strong> results found
                          <span v-if="query !== ''">for <strong>"{{ query }}"</strong></span>
                          in <strong>{{ processingTime }}ms</strong>
                        </div>
                      </ais-stats>
                    </div>
                </div>
                <div id="pagination">
                    <!-- Uncomment the following widget to add pagination -->
                    <ais-pagination>
                      <span slot="first">«</span>
                      <span slot="previous">‹</span>
                      <span slot="next">›</span>
                      <span slot="last">»</span>
                    </ais-pagination>
                </div>
            </div>
            <div class="left-panel">
                <div id="categories">
                    <!-- Uncomment the following widget to add categories list -->
                     <refinement-list attribute-name="channel.name" header-title="Channels"/>
                </div>
            </div>
        </main>
        <footer>

        </footer>
    </ais-index>
</template>

<script>
    import Hit from './Hit'
    import RefinementList from './RefinementList'

    export default {
        components: {
            Hit,
            RefinementList
        },
        data() {
            return {
                ALGOLIA_KEY: process.env.VUE_APP_ALGOLIA_KEY,
                ALGOLIA_APP_ID: process.env.VUE_APP_ALGOLIA_APP_ID
            }
        }
    }
</script>

<style src="../assets/styles.css"></style>
