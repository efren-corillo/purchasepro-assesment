<script setup>
  import { onMounted, ref, computed, watch, watchEffect, reactive } from 'vue'
  import { shopStore } from '@/js/store/shop'
  import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
  import { CheckCircleIcon, PlusIcon, MinusIcon, TrashIcon } from '@heroicons/vue/20/solid'
  import { confirmOrderApi, deleteCartItemApi, updateCartQuantity } from '@/js/api/product'
  import { inject } from 'vue'

  import router from '@/js/router'

  const swal = inject('$swal')

  const store = shopStore()

  const isLoading = ref( false )

  const cartItems = ref( [] )

  const customer = ref( {
    email: '',
    firstName: '',
    lastName: '',
    company: '',
    address: '',
    apartetc: '',
    city: '',
    country: 'United States',
    state: '',
    postal: '',
    phone: '',
    card: {
      number: '',
      name: '',
      expiration: '',
      cvc: ''
    }
  } )

  const quantities = reactive( {} )

  // Initialize or update quantities based on cartItems
  watchEffect( () => {
    cartItems.value.forEach( product => {
      if ( !quantities[product.id] ) {
        quantities[product.id] = product.quantity
      }
    } )
  } )

  const createDebounce = () => {
    let timeout = null
    return function ( fnc, delayMs = 500 ) {
      clearTimeout( timeout )
      timeout = setTimeout( fnc, delayMs )
    }
  }

  const debounce = createDebounce()

  const isValidEmail = computed( () => {
    const regexPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return regexPattern.test( customer.value.email )
  } )

  const isFirstNameValid = computed( () => customer.value.firstName.trim().length > 0 )
  const isLastNameValid = computed( () => customer.value.lastName.trim().length > 0 )
  const isAddressValid = computed( () => customer.value.address.trim().length > 0 )
  const isApartetcValid = computed( () => customer.value.apartetc.trim().length > 0 )
  const isCityValid = computed( () => customer.value.city.trim().length > 0 )
  const isStateValid = computed( () => customer.value.state.trim().length > 0 )
  const isPostalValid = computed( () => customer.value.postal.trim().length > 0 )
  const isCardNumberValid = computed( () => customer.value.card.number.trim().length > 0 )
  const isCardNameValid = computed( () => customer.value.card.name.trim().length > 0 )
  const isCardExpirationValid = computed( () => customer.value.card.expiration.trim().length > 0 )
  const isCardCvcValid = computed( () => customer.value.card.cvc.trim().length > 0 )


  const isFormValid = computed( () => {
    return (
      isValidEmail.value &&
      isFirstNameValid.value &&
      isLastNameValid.value &&
      isAddressValid.value &&
      isApartetcValid.value &&
      isCityValid.value &&
      isStateValid.value &&
      isPostalValid.value &&
      isCardNumberValid.value &&
      isCardNameValid.value &&
      isCardExpirationValid.value &&
      isCardCvcValid.value &&
      cartItems.value.length !== 0
    )
  } )

  const deliveryMethods = ref( [
    { id: 1, title: 'Standard', turnaround: '4–10 business days', price: 5 },
    { id: 2, title: 'Express', turnaround: '2–5 business days', price: 16 }
  ] )

  const selectedDeliveryMethod = ref( deliveryMethods.value[0] )

  const shippingFee = ref( selectedDeliveryMethod.value.price )

  const subTotal = ref( 0 )
  const taxes = ref( 5.55 )

  const paymentMethods = ( [
    { id: 'credit-card', title: 'Credit card' }
  ] )

  const deleteCartItem = ( id ) => {
    deleteCartItemApi( id ).then( res => {
      if ( res ) {
        const index = cartItems.value.findIndex( item => item.id === id )
        if ( index > -1 ) {
          cartItems.value.splice( index, 1 )
        }
        computeSubTotal()
        store.triggerNotificationAction( res.message, 'success' )
      } else {
        store.triggerNotificationAction( 'Quantity update failed.', 'failed' )
      }
    } )
  }

  const updateQuantity = ( productId, value ) => {

    const product = cartItems.value.find( p => p.id === productId )

    if ( product ) {
      updateCartQuantity( { "id": product.id, "quantity": value } )
        .then( res => {
          if ( res.data || res.data !== undefined ) {
            product.quantity = value
            quantities[productId] = value
            computeSubTotal()
            store.triggerNotificationAction( 'Quantity updated successfully.', 'success' )
          } else {
            store.triggerNotificationAction( 'Quantity update failed.', 'failed' )
          }
        } )
    } else {
      store.triggerNotificationAction( 'Quantity update failed.', 'failed' )
    }
  }
  const DeductQuantity = ( id ) => {
    const item = cartItems.value.find( obj => obj.id === id )
    if ( item.quantity > 1 ) {
      const qty = item.quantity - 1

      updateCartQuantity( { "id": id, "quantity": qty } )
        .then( res => {
          if ( res.data || res.data !== undefined ) {
            item.quantity -= 1
            quantities[id] -= 1
            computeSubTotal()
            store.triggerNotificationAction( 'Quantity updated successfully.', 'success' )
          } else {
            store.triggerNotificationAction( 'Quantity update failed.', 'failed' )
          }
        } )
    } else {
      store.triggerNotificationAction( 'Quantity must not be below 0. Did you mean to delete the item?.', 'failed' )
    }
  }
  const AddQuantity = ( id ) => {
    const item = cartItems.value.find( obj => obj.id === id )
    const qty = item.quantity + 1

    updateCartQuantity( { "id": id, "quantity": qty } )
      .then( res => {
        if ( res.data || res.data !== undefined ) {
          item.quantity += 1
          quantities[id] += 1
          computeSubTotal()
          store.triggerNotificationAction( 'Quantity updated successfully.', 'success' )
        } else {
          store.triggerNotificationAction( 'Quantity update failed.', 'failed' )
        }
      } )
  }

  const computeSubTotal = () => {
    let subtotal = 0

    cartItems.value.forEach( ( item, key ) => {
      subtotal += Number( Number( item.product.price ) * Number( item.quantity ) )
    } )

    subTotal.value = Number( subtotal.toFixed( 2 ) )
  }

  const confirmOrder = () => {
    isLoading.value = true

    confirmOrderApi( {
      'customer': customer.value,
      'items': cartItems.value,
      'subtotal': subTotal.value,
      'shipping_fee': shippingFee.value,
      'taxes': taxes.value
    } )
      .then( res => {
        if ( res.status == 200 ) {
          resetAllFields()
          store.triggerNotificationAction( res.message, 'success' )

          swal.fire( {
            title: 'Success!',
            text: 'Your order is on route. Please check your email for the order details.',
            icon: 'info'
          } ).then( () => {
            router.push('/catalog')
          } )
        } else {
          store.triggerNotificationAction( 'Processing order failed.', 'failed' )
        }
      } ).finally( () => {
      isLoading.value = false
    } )
  }

  const resetAllFields = () => {
    getAllCartItems()

    subTotal.value = 0
    taxes.value = 5.55
    cartItems.value = []

    customer.value = {
      email: '',
      firstName: '',
      lastName: '',
      company: '',
      address: '',
      apartetc: '',
      city: '',
      country: 'United States',
      state: '',
      postal: '',
      phone: '',
      card: {
        number: '',
        name: '',
        expiration: '',
        cvc: ''
      }
    }
  }

  const getAllCartItems = () => {
    // get all cart Items
    store.getAllCartItemsWithProduct()
      .then( res => {
        cartItems.value = res

        computeSubTotal()
      } )
  }

  watch( selectedDeliveryMethod, ( newM, oldM ) => {
    shippingFee.value = newM.price

  } )

  onMounted( () => {
    getAllCartItems()
  } )
</script>

<template>
  <div class="bg-gray-50">
    <main class="mx-auto max-w-7xl px-4 pb-24 pt-16 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-2xl lg:max-w-none">
        <h1 class="sr-only">Checkout</h1>

        <form class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
          <div>
            <div>
              <h2 class="text-lg font-medium text-gray-900">Contact information</h2>

              <div class="mt-4">
                <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                <div class="mt-1">
                  <input
                    type="email"
                    v-model="customer.email"
                    id="email-address"
                    name="email-address"
                    autocomplete="email"
                    :class="['block w-full rounded-md border-gray-300 shadow-sm sm:text-sm',
                            {'focus:border-indigo-500 focus:ring-indigo-500': isValidEmail,
                            'border-red-500': !isValidEmail && customer.email.length > 0}]"
                  />
                  <p v-if="customer.email.length === 0" class="text-red-500 text-xs mt-1">
                    This field is Required.
                  </p>
                  <p v-if="!isValidEmail && customer.email.length > 0" class="text-red-500 text-xs mt-1">
                    Please enter a valid email address.
                  </p>
                </div>
              </div>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
              <h2 class="text-lg font-medium text-gray-900">Shipping information</h2>

              <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                <div>
                  <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.firstName" id="first-name" name="first-name"
                           autocomplete="given-name"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                    <p v-if="!isFirstNameValid" class="text-red-500 text-xs mt-1">
                      First Name Required.
                    </p>
                  </div>
                </div>

                <div>
                  <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.lastName" id="last-name" name="last-name"
                           autocomplete="family-name"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isLastNameValid" class="text-red-500 text-xs mt-1">
                      Last Name Required.
                    </p>
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.company" name="company" id="company"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.address" name="address" id="address"
                           autocomplete="street-address"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isAddressValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label for="apartment" class="block text-sm font-medium text-gray-700">Apartment, suite, etc.</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.apartetc" name="apartment" id="apartment"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isApartetcValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div>
                  <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.city" name="city" id="city" autocomplete="address-level2"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isCityValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div>
                  <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                  <div class="mt-1">
                    <select id="country" v-model="customer.country" name="country" autocomplete="country-name"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      <option>United States</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label for="region" class="block text-sm font-medium text-gray-700">State / Province</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.state" name="region" id="region" autocomplete="address-level1"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isStateValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div>
                  <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal code</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.postal" name="postal-code" id="postal-code"
                           autocomplete="postal-code"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isPostalValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                  <div class="mt-1">
                    <input type="text" v-model="customer.phone" name="phone" id="phone" autocomplete="tel"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-10">
              <RadioGroup v-model="selectedDeliveryMethod">
                <RadioGroupLabel class="text-lg font-medium text-gray-900">Delivery method</RadioGroupLabel>

                <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                  <RadioGroupOption as="template" v-for="deliveryMethod in deliveryMethods" :key="deliveryMethod.id"
                                    :value="deliveryMethod" v-slot="{ checked, active }">
                    <div
                      :class="[checked ? 'border-transparent' : 'border-gray-300', active ? 'ring-2 ring-indigo-500' : '', 'relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none']">
                      <span class="flex flex-1">
                        <span class="flex flex-col">
                          <RadioGroupLabel as="span" class="block text-sm font-medium text-gray-900">{{
                              deliveryMethod.title
                            }}</RadioGroupLabel>
                          <RadioGroupDescription as="span" class="mt-1 flex items-center text-sm text-gray-500">{{
                              deliveryMethod.turnaround
                            }}</RadioGroupDescription>
                          <RadioGroupDescription as="span" class="mt-6 text-sm font-medium text-gray-900">{{
                              deliveryMethod.price
                            }}</RadioGroupDescription>
                        </span>
                      </span>
                      <CheckCircleIcon v-if="checked" class="h-5 w-5 text-indigo-600" aria-hidden="true"/>
                      <span
                        :class="[active ? 'border' : 'border-2', checked ? 'border-indigo-500' : 'border-transparent', 'pointer-events-none absolute -inset-px rounded-lg']"
                        aria-hidden="true"/>
                    </div>
                  </RadioGroupOption>
                </div>
              </RadioGroup>
            </div>

            <!-- Payment -->
            <div class="mt-10 border-t border-gray-200 pt-10">
              <h2 class="text-lg font-medium text-gray-900">Payment</h2>

              <fieldset class="mt-4">
                <legend class="sr-only">Payment type</legend>
                <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                  <div v-for="(paymentMethod, paymentMethodIdx) in paymentMethods" :key="paymentMethod.id"
                       class="flex items-center">
                    <input v-if="paymentMethodIdx === 0" :id="paymentMethod.id" name="payment-type" type="radio"
                           checked="" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                    <input v-else :id="paymentMethod.id" name="payment-type" type="radio"
                           class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                    <label :for="paymentMethod.id"
                           class="ml-3 block text-sm font-medium text-gray-700">{{ paymentMethod.title }}</label>
                  </div>
                </div>
              </fieldset>

              <div class="mt-6 grid grid-cols-4 gap-x-4 gap-y-6">
                <div class="col-span-4">
                  <label for="card-number" class="block text-sm font-medium text-gray-700">Card number</label>
                  <div class="mt-1">
                    <input v-model="customer.card.number" type="text" id="card-number" name="card-number"
                           autocomplete="cc-number"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isCardNumberValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div class="col-span-4">
                  <label for="name-on-card" class="block text-sm font-medium text-gray-700">Name on card</label>
                  <div class="mt-1">
                    <input v-model="customer.card.name" type="text" id="name-on-card" name="name-on-card"
                           autocomplete="cc-name"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isCardNameValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div class="col-span-3">
                  <label for="expiration-date" class="block text-sm font-medium text-gray-700">Expiration date
                    (MM/YY)</label>
                  <div class="mt-1">
                    <input v-model="customer.card.expiration" type="text" name="expiration-date" id="expiration-date"
                           autocomplete="cc-exp"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isCardExpirationValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>

                <div>
                  <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                  <div class="mt-1">
                    <input v-model="customer.card.cvc" type="text" name="cvc" id="cvc" autocomplete="csc"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <p v-if="!isCardCvcValid" class="text-red-500 text-xs mt-1">
                      This field is Required.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order summary -->
          <div class="mt-10 lg:mt-0">
            <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

            <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
              <h3 class="sr-only">Items in your cart</h3>
              <ul role="list" class="divide-y divide-gray-200">
                <li v-for="product in cartItems" :key="product.id" class="flex px-4 py-6 sm:px-6">
                  <div class="flex-shrink-0">
                    <img :src="product.product.image" :alt="product.product.title" class="w-20 rounded-md"/>
                  </div>

                  <div class="ml-6 flex flex-1 flex-col">
                    <div class="flex">
                      <div class="min-w-0 flex-1">
                        <h4 class="text-sm">
                          <a :href="product.href" class="font-medium text-gray-700 hover:text-gray-800 capitalize">{{
                              product.product.title
                            }}</a>
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">{{ product.product.description }}</p>
                      </div>

                      <div class="ml-4 flow-root flex-shrink-0">
                        <button
                          type="button"
                          class="-m-2.5 flex items-center justify-center bg-white p-2.5 text-gray-400 hover:text-red-500"
                          @click="deleteCartItem(product.id)"
                        >
                          <span class="sr-only">Remove</span>
                          <TrashIcon class="h-5 w-5" aria-hidden="true"/>
                        </button>
                      </div>
                    </div>

                    <div class="flex flex-1 items-center justify-between pt-2">
                      <p class="text-sm font-medium text-gray-700">${{ Number( product.product.price ) }} x
                        {{ product.quantity }} = </p>
                      <p class="text-sm font-medium text-gray-900">
                        ${{ ( Number( product.product.price ) * product.quantity ).toFixed( 2 ) }}</p>

                      <div class="flex items-center space-x-2">
                        <button
                          class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none"
                          type="button"
                          @click="DeductQuantity(product.id)"
                        >
                          <MinusIcon class="h-5 w-5" aria-hidden="true"/>
                        </button>
                        <input
                          class="w-full rounded-md border-gray-300 py-2 text-center text-gray-900 ring-indigo-600 focus:ring-2"
                          type="text"
                          name="quantity"
                          style="max-width: 4rem;"
                          autocomplete="off"
                          :value="quantities[product.id]"
                          @input="debounce(() => updateQuantity(product.id, $event.target.value))"
                        >
                        <button
                          class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none"
                          type="button"
                          @click="AddQuantity(product.id)"
                        >
                          <PlusIcon class="h-5 w-5" aria-hidden="true"/>
                        </button>
                      </div>
                    </div>

                  </div>
                </li>
              </ul>
              <dl class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6">
                <div class="flex items-center justify-between">
                  <dt class="text-sm">Subtotal</dt>
                  <dd class="text-sm font-medium text-gray-900">${{ Number( subTotal ) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt class="text-sm">Shipping</dt>
                  <dd class="text-sm font-medium text-gray-900">${{ Number( shippingFee ) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt class="text-sm">Taxes</dt>
                  <dd class="text-sm font-medium text-gray-900">${{ Number( taxes ) }}</dd>
                </div>
                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                  <dt class="text-base font-medium">Total</dt>
                  <dd class="text-base font-medium text-gray-900">
                    ${{ ( Number( subTotal ) + Number( shippingFee ) + Number( taxes ) ).toFixed( 2 ) }}
                  </dd>
                </div>
              </dl>

              <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                <button
                  @click="confirmOrder"
                  :disabled="!isFormValid || isLoading"
                  type="button"
                  class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 cursor-pointer"
                >
                  <span v-if="isLoading">Processing Order...</span>
                  <span v-else>Confirm order</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>
