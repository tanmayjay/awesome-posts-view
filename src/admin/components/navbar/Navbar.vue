<template>
    <div class="apv-admin-navbar">
        <RouterLink
            v-for="(item, key) in navLinks"
            :key="key"
            :to="key"
            :class="navItemClass(key)"
        >
            {{ item }}
        </RouterLink>
    </div>
</template>

<script>
export default {
    name: 'NavbarComponent',

    props: {
        navItems: {
            type: Object,
            default: () => {},
        },
    },

    computed: {
        navLinks() {
            if ( this.isEmpty( this.navItems ) ) {
                return {
                    table: this.__( 'Table', 'apv' ),
                    graph: this.__( 'Graph', 'apv' ),
                    settings: this.__( 'Settings', 'apv' ),
                };
            }

            return this.navItems;
        },
    },

    methods: {
        navItemClass(key) {
            let className   = `nav-item ${key}`,
                path        = this.$route.path.toString().trim().split("/"),
                currentPath = path[path.length - 1];

            if (currentPath === key) {
                className += ' active';
            }

            return className;
        }
    }
}
</script>

<style lang="less">
.apv-admin-navbar {
    background: #fff;
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.1);
    font-size: 15px;
    font-weight: 400;
    height: 54px;
    margin: -9px -20px 20px -20px;
    padding: 0 20px;

    a {
        box-shadow: none;
        color: #6e6e6e;
        display: inline-block;
        padding: 18px 18px 16px 18px;
        margin: 0 2px;
        text-decoration: none;
        cursor: pointer;

        &:focus,
        &:active {
            box-shadow: none;
            outline: none;
        }

        &:hover,
        &.router-link-active {
            border-bottom: 3px solid rgb(44, 186, 243);
        }
    }
}
</style>
