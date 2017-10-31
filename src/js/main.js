import Accordion from '../vue/Accordion.vue';
import Contact from '../vue/Contact.vue';
import Header from '../vue/Header.vue';
import OrderedList from '../vue/OrderedList.vue';
import PageContainer from '../vue/PageContainer.vue';
import RoleIntro from '../vue/RoleIntro.vue';
import Search from '../vue/Search.vue';
import Tabs from '../vue/Tabs.vue';
import TextBlock from '../vue/TextBlock.vue';
import TextImageBlock from '../vue/TextImageBlock.vue';
import VideoPlayer from '../vue/VideoPlayer.vue';
import Vue from 'vue';

function toggleOpenNavMenu(navLink) {
    console.log('toggleOpenNavMenu');
    const openClassName = 'page-container--nav-menu-open';
    const pageContainer = navLink.closest('.page-container.js');
    if (pageContainer.classList.contains(openClassName)) {
        pageContainer.classList.remove(openClassName);
    } else {
        pageContainer.classList.add(openClassName);
    }
}

window.onload = function() {

    (function addAccordionFillerContent() {  // TODO remove this before launch
        const elements = document.getElementsByClassName('accordion__list-element js');
        for (let i = 0; i < elements.length; i++) {
            const content = elements[i].getElementsByClassName('accordion__list-element-content')[0];
            content.innerHTML = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        }
    })();

    Vue.component('accordion', Accordion);
    Vue.component('accordion-element', Accordion.childComponents.Element);
    Vue.component('contact', Contact);
    Vue.component('page-header', Header);
    Vue.component('ordered-list', OrderedList);
    Vue.component('ol-element', OrderedList.childComponents.Element);
    Vue.component('page-container', PageContainer);
    Vue.component('role-intro', RoleIntro);
    Vue.component('search', Search);
    Vue.component('tabs', Tabs);
    Vue.component('tab', Tabs.childComponents.Tab);
    Vue.component('text-block', TextBlock);
    Vue.component('text-image-block', TextImageBlock);
    Vue.component('video-player', VideoPlayer);

    new Vue({
        el: '#site-container'
    })
};