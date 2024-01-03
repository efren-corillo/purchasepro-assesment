<script setup>
  import { ref , watch, onMounted} from 'vue'
  import { useRoute } from 'vue-router';

  import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
  import { Bars3Icon, BellIcon, ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/solid'
  import logo from '@images/logo.png'
  import CartTopIcon from '@/js/components/CartTopIcon.vue'

  const route = useRoute()

  const showMenu = ref( false )

  const links = ref([
    { to : '/', label: 'home', icon: 'fa-home', current: true},
    { to : '/catalog', label: 'catalog', icon: 'fa-basket-shopping', current: false },
    { to : '/about', label: 'about', icon: 'fa-circle-info', current: false}
  ])

  const toggleNavbar = () => {
    showMenu.value = !showMenu.value
  }

  const changeRoute = () => {
    showMenu.value = false;
  }

  const correctCurrentActive = ( newPath ) => {
    links.value.forEach(link => {
      link.current = link.to === newPath
    })
  }

  watch(
    () => route.path,
    (newPath) => {
      correctCurrentActive(newPath)
    }
  )

  onMounted(() => {
    correctCurrentActive(route.path)
  })
</script>

<template>
  <div class="relative">
    <Disclosure v-slot="{ open }" as="nav" class="pp_top_nav">
      <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
          <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
            <!-- Mobile menu button-->
            <DisclosureButton class="pp_top_nav--menu-btn">
              <span class="absolute -inset-0.5" />
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!open" aria-hidden="true" class="block h-6 w-6" />
              <XMarkIcon v-else aria-hidden="true" class="block h-6 w-6" />
            </DisclosureButton>
          </div>
          <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
            <div class="flex flex-shrink-0 items-center">
              <img :src="logo" alt="Your Company" class="h-8 w-auto" />
            </div>
            <div class="hidden sm:ml-6 sm:block">
              <div class="flex space-x-4">
                <router-link
                  v-for="link in links"
                  :key="link.label"
                  :aria-current="link.current ? 'page' : undefined"
                  :class="[link.current ? 'bg-yellow-500 text-blue-800' : 'text-yellow-400 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-xs uppercase font-medium']"
                  :to="link.to"
                >
                  {{ link.label }}
                </router-link>
              </div>
            </div>
          </div>
          <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
            <button class="pp_top_nav--right-button" type="button">
              <span class="absolute -inset-1.5" />
              <span class="sr-only">View notifications</span>
              <BellIcon aria-hidden="true" class="h-6 w-6" />
            </button>

            <cart-top-icon />

            <!-- Profile dropdown -->
            <!-- <Menu as="div" class="relative ml-3">
              <div>
                <MenuButton class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Your Profile</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>-->
          </div>
        </div>
      </div>

      <DisclosurePanel class="sm:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2">
          <DisclosureButton
            v-for="link in links"
            :key="link.label"
            :aria-current="link.current ? 'page' : undefined"
            :class="[link.current ? 'bg-yellow-300 text-blue-950 hover:text-gray-600' : 'text-yellow-300 hover:bg-blue-950 hover:text-white', 'block rounded-md px-3 py-3 my-2 text-md uppercase font-medium']"
            :href="link.to" as="a"
          >
            <i
              :class="link.icon"
              class="fa-solid pr-2"
            />{{ link.label }}
          </DisclosureButton>
        </div>
      </DisclosurePanel>
    </Disclosure>
  </div>

</template>

<style lang="scss">
</style>