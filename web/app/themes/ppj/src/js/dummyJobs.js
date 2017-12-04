
const dummyJobs = [

    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£31,560",
        organizationName : "HMP Brixton",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/xf-a5f8e63220f3/candidate/so/pm/1/pl/3/opp/14959-201712-Prison-Officer-HMP-Brixton-HMP-Wandsworth/en-GB',
        lat: 51.4518434,
        lng: -0.1247506
    },
    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£31,560",
        organizationName : "HMP Wandsworth",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/xf-a5f8e63220f3/candidate/so/pm/1/pl/3/opp/14959-201712-Prison-Officer-HMP-Brixton-HMP-Wandsworth/en-GB',
        lat: 51.4500084,
        lng: -0.1863312
    },
    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£28,950",
        organizationName : "HMP Huntercombe",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/candidate/so/pm/1/pl/3/opp/9935-201706-Prison-Officer-HMP-Huntercombe/en-GB?utm_source=Indeed&utm_medium=organic&utm_campaign=Indeed',
        lat: 51.5874,
        lng: -1.0204887
    },
    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£31,981",
        organizationName : "HMP/YOI Feltham",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/candidate/so/pm/1/pl/3/opp/12797-201709-Prison-Officer-HMYOI-Feltham/en-GB?utm_source=Indeed&utm_medium=organic&utm_campaign=Indeed',
        lat: 51.4415566,
        lng: -0.4362452
    },
    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£31,981",
        organizationName : "HMP/YOI Pentonville",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/xf-a5f8e63220f3/candidate/so/pm/1/pl/3/opp/12836-201709-Prison-Officer-HMP-YOI-Pentonville-HMP-YOI-Wormwood-Scrubs/en-GB',
        lat: 51.5449765,
        lng: -0.1182413
    },
    {
        distance: "",
        distanceTime: '',
        position: "Prison Officer",
        salary: "£31,981",
        organizationName : "HMP/YOI Wormwood Scrubs",
        organizationCity : "London",
        url: "/job-post.html",
        link: 'https://justicejobs.tal.net/vx/lang-en-GB/mobile-0/appcentre-1/brand-2/candidate/so/pm/1/pl/3/opp/12797-201709-Prison-Officer-HMYOI-Feltham/en-GB?utm_source=Indeed&utm_medium=organic&utm_campaign=Indeed',
        lat: 51.5169637,
        lng: -0.2425305
    },
    /*,
    {
        distance: "1.03 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£22,396",
        organizationName : "HMP Pentonville", organizationPageLink: '/hmp-pentonville.html',
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.5449765,
        lng: -0.1182413
    },
    {
        distance: "1.15 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£22,396",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£29,981",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.30 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£26,950",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },

    // page 2
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£41,560",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.03 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£32,396",
        organizationName : "HMP Pentonville", organizationPageLink: '/hmp-pentonville.html',
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.5449765,
        lng: -0.1182413
    },
    {
        distance: "1.15 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£32,396",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£39,981",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.30 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£36,950",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },

    // page 3
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£19,560",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.03 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£32,396",
        organizationName : "HMP Pentonville", organizationPageLink: '/hmp-pentonville.html',
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.5449765,
        lng: -0.1182413
    },
    {
        distance: "1.15 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£32,396",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£39,981",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.30 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£36,950",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },

    // page 4
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£51,560",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.03 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£42,396",
        organizationName : "HMP Pentonville", organizationPageLink: '/hmp-pentonville.html',
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.5449765,
        lng: -0.1182413
    },
    {
        distance: "1.15 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£42,396",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "0.88 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£49,981",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    },
    {
        distance: "1.30 miles",
        distanceTime: '', position: "Prison Officer",
        salary: "£46,950",
        organizationName : "HMP Belmarsh",
        organizationCity : "London",
        url: "/job-post.html",
        lat: 51.4961652,
        lng: 0.0910377
    }*/
];

export default dummyJobs;