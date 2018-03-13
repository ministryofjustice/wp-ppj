<template>
  <div class="video-player"
       :class="hostClass"
  >
    <img :src="coverImage"
         v-if="coverImageVisible"
         class="video-player__cover-image"
    />
    <div class="video-player__play-button-container"
         v-if="coverImageVisible"
    >
      <svg class="video-player__play-button"
           x="0px"
           y="0px"
           viewBox="0 0 138.3 138.3"
           style="enable-background:new 0 0 138.3 138.3;"
           @click="playVideo"
      >
        <path class="st0" d="M69.2,8c33.7,0,61.2,27.4,61.2,61.2s-27.4,61.2-61.2,61.2S8,102.9,8,69.2S35.4,8,69.2,8 M69.2,0
        C31,0,0,31,0,69.2s31,69.2,69.2,69.2s69.2-31,69.2-69.2S107.4,0,69.2,0L69.2,0z"/>
        <polygon class="st0" points="96.3,69.2 48.4,41.5 48.4,96.8 96.3,69.2 48.4,41.5 48.4,96.8 	"/>
      </svg>
    </div>
    <div class="video-player__video-container"
         :id="id"
         v-if="!coverImageVisible"
    >
      <iframe :src="iframeSrc"
              allowtransparency="true"
              frameborder="0"
              scrolling="no"
              allowfullscreen
              mozallowfullscreen
              webkitallowfullscreen
              oallowfullscreen
              msallowfullscreen
      ></iframe>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      host: {
          type: String,
          default: 'youtube'
      },
      id: {
          type: String,
          default: ''
      },
      coverImage: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
        videoPlaying: this.coverImage == '',
      }
    },
    computed: {
      coverImageVisible: function() {
        // autoplay does not work for RaptMedia videos on mobile devices
        // http://docs.raptmedia.com/#hotspot-editing
        if (this.host == 'raptmedia') {
          return false;
        } else {
          return (this.coverImage !== '' && !this.videoPlaying);
        }
      },
      hostClass: function() {
        return 'video-player--' + this.host;
      },
      iframeSrc: function() {
        let src = '';
        switch (this.host) {
          case 'wistia':
            const autoPlayParam = (this.coverImage !== '') ? '?autoPlay=true' : '';
            src = '//fast.wistia.net/embed/iframe/' + this.id + autoPlayParam;
            break;
          case 'raptmedia':
            src = 'https://cdn1.raptmedia.com/projects/' + this.id + '/embed?autoplay=false&controls=below'
        }
        return src;
      }
    },
    methods: {
      playVideo: function() {
        this.videoPlaying = true;
      }
    }
  }
</script>
