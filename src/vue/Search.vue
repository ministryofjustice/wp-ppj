<template>
        <div class="search">
            <h2 class="search__title">{{ titleText }}</h2>
            <form class="search__form">
                <input
                        type="text"
                        class="search__input"
                        :placeholder="placeHolderText"
                        editable="editable"
                />
                <button class="search__button">
                    <div class="search__button-circle"></div>
                    <div class="search__button-rectangle"></div>
                </button>
            </form>
        </div>
</template>

<script>
    export default {
        data() {
            return {
                titleText: 'Search for vacancies near you',
                placeHolderText: 'Enter your post code'
            }
        }
    }
</script>

<style>

</style>