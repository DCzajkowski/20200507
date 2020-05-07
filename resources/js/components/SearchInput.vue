<template>
    <div class="search-item-wrapper" ref="searchItemWrapper">
        <label for="search-item">
            <input
                type="text"
                id="search-item"
                ref="searchItem"
                @input="fetchData"
                :value="text"
                autocomplete="off"
            >
            <input type="hidden" :name="fieldName" :value="activeItemID">
        </label>

        <div class="search-items" v-if="active">
            <div v-if="items === null">
                Loading...
            </div>
            <div v-else-if="items.length === 0">
                No results.
            </div>
            <div
                v-else
                class="search-item"
                v-for="(item, index) in items"
                :key="item.id"
                @click="onItemClick(item)"
                :class="{
                    'search-item--active': activeItem === index,
                }"
            >
                {{ item[itemKey] }}
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import axios from 'axios'

    export default {
        props: {
            url: String,
            itemKey: String,
            fieldName: String,
        },
        data() {
            return {
                text: '',
                items: null,
                active: false,
                activeItem: 0,
            }
        },
        computed: {
            activeItemID() {
                return this.items && this.activeItem < this.items.length ? this.items[this.activeItem].id : null
            },
        },
        methods: {
            fetchData: _.debounce(async function ({ target: { value: search } }) {
                this.text = search
                this.active = true
                this.activeItem = 0

                const response = await axios.get(`${this.url}?search=${search}`)
                const data = response.data.data

                this.items = data
            }, 500),
            onItemClick(item) {
                this.text = item[this.itemKey]
                this.active = false
            },
            keyup({ key }) {
                if (this.active && key === 'Escape') {
                    this.active = false
                }

                if (this.active && (key === 'Enter' || key === ' ')) {
                    this.onItemClick(this.items[this.activeItem])
                    this.$refs.searchItem.focus()
                }

                if (key === 'ArrowDown') {
                    this.activeItem += 1
                    this.$refs.searchItem.blur()
                }

                if (key === 'ArrowUp') {
                    this.activeItem -= 1
                    this.$refs.searchItem.blur()
                }

                if (this.activeItem >= (this.items || []).length) {
                    this.activeItem = 0
                }

                if (this.activeItem < 0) {
                    this.activeItem = (this.items || []).length - 1
                }
            },
            clickOnPage({ target }) {
                if (!this.$refs.searchItemWrapper.contains(target)) {
                    this.active = false
                }
            },
        },
        mounted() {
            document.addEventListener('keyup', this.keyup)
            document.addEventListener('click', this.clickOnPage)
        },
        beforeDestroy() {
            document.removeEventListener('keyup', this.keyup)
            document.removeEventListener('click', this.clickOnPage)
        },
    }
</script>

<style>
    .search-item-wrapper {
        position: relative;
        display: inline-flex;
    }
    .search-items {
        position: absolute;
        bottom: 0;
        transform: translateY(100%);
        padding: 1rem .25rem;
        background: white;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .search-item {
        padding: .25rem 1rem;
    }
    .search-item:hover,
    .search-item--active {
        background: #ccc;
        cursor: pointer;
    }
</style>
