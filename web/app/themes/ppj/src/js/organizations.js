const categories = {
    'category-b': {
        displayName: 'Category B',
        description: '',
        organizations: [
            'hmp-brixton',
            'hmp-lowdam-grange',
            'hmp-peterborough'
        ]
    },
    'category-b-local-with-restricted-status-female-function': {
        displayName: 'Category B local with restricted status female function',
        description: '',
        organizations: [
            'hmp-durham'
        ]
    },
    'category-b-c-and-d': {
        displayName: 'Category B, C and D',
        description: '',
        organizations: [
            'hmp-hewell',
            'hmp-norwich'
        ]
    },
    'category-c-d-semi-open': {
        displayName: 'Category C/D semi-open',
        description: '',
        organizations: [
            'hmp-kirklevington-grange'
        ]
    },
};

const regions = {
    'east-midlands': {
        displayName: 'East Midlands',
        organizations: [
            'foston-hall',
            'gartree',
            'glen-parva',
            'leicester',
            'lincoln',
            'lowdham-grange',
            'morton-hall',
            'new-hall',
            'north-sea-camp',
            'nottingham',
            'onley',
            'ranby',
            'rye-hill',
            'stocken',
            'sudbury',
            'whatton'
      ]
    },
    'east-midlands': {
        displayName: 'East Midlands',
        organizations: [
            'bedford',
            'blundeston',
            'bullwood-hall',
            'bure',
            'chelmsford',
            'highpoint-north',
            'highpoint-south',
            'hollesley-bay',
            'littlehey',
            'new-hall',
            'norwich',
            'peterborough',
            'the-mount',
            'warren-hill',
            'Wayland',
            'Whitemoor'
        ]
    }
};

const details = {
    'hmp-altcourse': {
        displayName: 'HMP Altcourse',
        address: {
            line1: 'Higher Lane',
            line2: 'Fazakerley',
            city: 'Liverpool',
            postCode: 'L9 7LH',
        },
        location: {
            lat: 51.4961652,
            lng: 0.0910377,
            region: ''
        },
        contact: {
            websiteURL: '',
            telephone: '0151 552 2000',
            fax: '0151 522 2121'
        },
        type: 'prison',
        categories: ['Category B']
    },
    'hmp-ashfield': {
        displayName: 'HMP Ashfield',
        address: {
            line1: 'Shortwood Road',
            line2: 'Pucklechurch',
            city: 'Bristol',
            postCode: 'BS16 9QJ',
        },
        location: {
            lat: 51.482446,
            lng: -2.438898,
            region: ''
        },
        contact: {
            websiteURL: '',
            telephone: '0117 303 8000',
            fax: '0117 303 8001'
        },
        type: 'prison',
        categories: ['Category C']
    },
    'hmp-belmarsh': {
        displayName: 'HMP Belmarsh',
        address: {
            line1: '',
            line2: '',
            city: 'London',
            postCode: '',
        },
        location: {
            lat: 51.4961652,
            lng: 0.0910377,
            region: ''
        },
        contact: {
            websiteURL: '',
            telephone: '',
            fax: ''
        },
        type: 'prison',
        categories: ['']
    }
};

export default {
      details
    , categories
    , regions
};
