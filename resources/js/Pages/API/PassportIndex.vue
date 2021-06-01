<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Clients
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="createApiClient">
                    <template #title>
                        Create API Token
                    </template>

                    <template #description>
                        API tokens allow third-party services to authenticate with our application on your behalf.
                    </template>

                    <template #form>
                        <!-- Token Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Name" />
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createApiTokenForm.name" autofocus />
                            <jet-input-error :message="createApiTokenForm.errors.name" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="redirect" value="Redirect"/>
                            <jet-input id="redirect" type="text" class="mt-1 block w-full" v-model="createApiTokenForm.redirect" />
                            <jet-input-error :message="createApiTokenForm.errors.redirect" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                            Created.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing">
                            Create
                        </jet-button>
                    </template>
                </jet-form-section>
                <div v-if="clients.length > 0">
                    <jet-section-border />

                    <!-- Manage API Tokens -->
                    <div class="mt-10 sm:mt-0">
                        <jet-action-section>
                            <template #title>
                                Manage API Tokens
                            </template>

                            <template #description>
                                You may delete any of your existing tokens if they are no longer needed.
                            </template>

                            <!-- API Token List -->
                            <template #content>
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between" v-for="client in clients" :key="client.id">
                                        <div>
                                            {{ client.name }}
                                        </div>


                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-400" v-if="client.redirect">
                                                callback: {{ client.redirect }}
                                            </div>


                                            <button class="cursor-pointer ml-6 text-sm text-red-500" @click="confirmApiClientDeletion(client)">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </jet-action-section>
                    </div>
                </div>
                <!-- Token Value Modal -->
                <jet-dialog-modal :show="displayingSecret" @close="displayingSecret = false">
                    <template #title>
                        API Token
                    </template>

                    <template #content>
                        <div>
                            Please copy your new API token. For your security, it won't be shown again.
                        </div>

                        <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500" v-if="$page.props.jetstream.flash.secret">
                            {{ $page.props.jetstream.flash.secret }}
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="displayingSecret = false">
                            Close
                        </jet-secondary-button>
                    </template>
                </jet-dialog-modal>

                <!-- Delete Token Confirmation Modal -->
                <jet-confirmation-modal :show="apiClientBeingDeleted.client" @close="apiClientBeingDeleted.client = null">
                    <template #title>
                        Borrar cliente API
                    </template>

                    <template #content>
                        Esta seguro que deseas borrar este cliente?
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="apiClientBeingDeleted.client = null">
                            Cancelar
                        </jet-secondary-button>

                        <jet-danger-button class="ml-2" @click="deleteApiClient" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing">
                            Borrar
                        </jet-danger-button>
                    </template>
                </jet-confirmation-modal>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import ApiTokenManager from './ApiTokenManager'
    import AppLayout from '@/Layouts/AppLayout'
    import axios from 'axios'
    import {onMounted, ref, reactive} from 'vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    export default {
        components: {
            ApiTokenManager,
            AppLayout,
            JetSectionBorder,
            JetActionSection,
            JetFormSection,
            JetLabel,
            JetInput,
            JetInputError,
            JetCheckbox,
            JetActionMessage,
            JetButton,
            JetDialogModal,
            JetSecondaryButton,
            JetConfirmationModal,
            JetDangerButton
        },
        props:{
          clients:{
              type:Array,
              required:true
          }
        },
        setup(_){
            const displayingSecret = ref(false)
            const createApiTokenForm = useForm({
                name: '',
                redirect:'',
            })

            const deleteApiTokenForm = useForm({})

            const apiClientBeingDeleted = reactive({client:null})

            const createApiClient = function (){
                createApiTokenForm.post(route('api.clients.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        displayingSecret.value = true
                        createApiTokenForm.reset()
                    }
                })
            }

            const confirmApiClientDeletion = function (client){
                apiClientBeingDeleted.client = client
            }

            const deleteApiClient = function() {
                deleteApiTokenForm.delete(route('api.clients.destroy', apiClientBeingDeleted.client.id), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (apiClientBeingDeleted.client = null),
                })
            }


            return {
                apiClientBeingDeleted,
                createApiTokenForm,
                displayingSecret,
                deleteApiTokenForm,
                createApiClient,
                confirmApiClientDeletion,
                deleteApiClient
            }
        },
    }
</script>
