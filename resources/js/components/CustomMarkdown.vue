<template>
    <Markdown class="md-body" v-highlightjs :plugins="plugins" :source="source"/>
</template>

<script>

import MarkdownTocDone from "markdown-it-toc-done-right";
import MarkdownSup from "markdown-it-sup";
import Markdown from "vue3-markdown-it";
import MarkdownItIns from "markdown-it-ins";
import MarkdownItMarked from "markdown-it-mark";
import MarkdownOtFootnote from "markdown-it-footnote";
import MarkdownEmoji from "markdown-it-emoji";
import MarkdownDeflist from "markdown-it-deflist";
import MarkdownAnchor from "markdown-it-anchor";
import MarkdownAbbr from "markdown-it-abbr";


export default {
    name: "CustomMarkdown",
    props: {
        source: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            plugins: [
                {
                    plugin: MarkdownTocDone
                },
                {
                    plugin: MarkdownSup
                },
                {
                    plugin: MarkdownItIns
                },
                {
                    plugin: MarkdownItMarked
                },
                {
                    plugin: MarkdownOtFootnote
                },
                {
                    plugin: MarkdownEmoji
                },
                {
                    plugin: MarkdownDeflist
                },
                {
                    plugin: MarkdownAnchor
                },
                {
                    plugin: MarkdownAbbr
                },

            ],

        }
    },
    components: { Markdown }
}
</script>

<style scoped>

</style>
