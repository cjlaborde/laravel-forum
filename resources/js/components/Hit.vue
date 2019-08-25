<template>
    <div>
        <div class="ais-hits--item">
            <article>
<!--                <div class="post-img">-->
<!--                    <a :href="result.permalink">-->
<!--                        <img :src="result.image">-->
<!--                    </a>-->
<!--                </div>-->
                <div class="post-content">
                    <div class="post-date">{{ result.post_date_formatted }}</div>
                    <h2 class="entry-title">
                        <a :href="result.permalink" rel="bookmark">
                            <ais-highlight :result="result" attribute-name="post_title"/>
                        </a>
                    </h2>
                    <div class="post-excerpt">
                        <a :href="result.path">
                            <h5 class="ml-3">
                                <ais-snippet :result="result" attribute-name="title"/>
                            </h5>
                        </a>
                        <div class="row ml-3">
                            <ais-snippet :result="result" attribute-name="body"/>
                        </div>
                    </div>
                    <div class="entry-meta clear">
                        <div class="entry-author-content">
                            <div class="author-name">
                                <ais-highlight :result="result" attribute-name="author_name"/>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <h2>
            <a :href="result.url">
<!--                {{ result.title }}-->
            </a>
        </h2>
        <p>{{ result.description }}</p>
    </div>
</template>

<script>
    export default {
        props: {
            result: Object
        }
    }
</script>
