<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Clients
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="createApiToken">
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
                            <!--
                            <jet-input-error :message="createApiTokenForm.errors.name" class="mt-2" />
                            -->
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="callback" value="Callback"/>
                            <jet-input type="text" class="mt-1 block w-full" v-model="createApiTokenForm.callback" />
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


                                            <button class="cursor-pointer ml-6 text-sm text-red-500" @click="confirmApiTokenDeletion(client)">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </jet-action-section>
                    </div>
                </div>
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
            JetButton
        },
        props:{
          clients:{
              type:Array,
              required:true
          }
        },
        setup(_){

            const createApiTokenForm = reactive({
                name: '',
                callback:'',
            })


            return {
                createApiTokenForm
            }
        },
    }
</script>
