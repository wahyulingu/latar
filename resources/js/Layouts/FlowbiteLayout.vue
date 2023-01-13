<script setup>
import { Avatar, Navbar, NavbarCollapse, Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import NavLink from '@/Components/Flowbite/NavLink.vue';
import ApplicationMark from '@/Components/Jetstream/ApplicationMark.vue';
import Banner from '@/Components/Jetstream/Banner.vue';


defineProps({
    title: String,
});

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};

</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100">

            <Navbar>
                <template #logo>
                    <Link :href="route('dashboard')">
                    <ApplicationMark class="block h-9 w-auto" />
                    </Link>
                </template>
                <template #default="{ isShowMenu }">
                    <NavbarCollapse :isShowMenu="isShowMenu">
                        <div class="flex justify-between">
                            <div>
                                <Dropdown class="md:hidden pb-3">
                                    <template #trigger>
                                        <div class="flex items-center">
                                            <div v-if="$page.props.jetstream.managesProfilePhotos"
                                                class="shrink-0 mr-3">
                                                <Avatar status="online" bordered rounded
                                                    :img="$page.props.user.profile_photo_url" class="mr-2.5" />
                                            </div>

                                            <div>
                                                <div class="font-medium text-base text-gray-800">
                                                    {{ $page.props.user.name }}
                                                </div>
                                                <div class="font-medium text-sm text-gray-500">
                                                    {{ $page.props.user.email }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <ListGroup>
                                        <ListGroupItem>
                                            <template #prefix>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </template>
                                            <Link :href="route('profile.show')">
                                            Profile
                                            </Link>
                                        </ListGroupItem>
                                        <ListGroupItem v-if="$page.props.jetstream.hasApiFeatures">
                                            <template #prefix>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                    </path>
                                                </svg>
                                            </template>

                                            <Link :href="route('api-tokens.index')">
                                            API Tokens
                                            </Link>
                                        </ListGroupItem>
                                        <form @submit.prevent="logout">
                                            <ListGroupItem>
                                                <template #prefix>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                        </path>
                                                    </svg>
                                                </template>
                                                <button>
                                                    Log Out
                                                </button>
                                            </ListGroupItem>
                                        </form>
                                    </ListGroup>
                                </Dropdown>
                            </div>

                            <div>
                                <Dropdown placement="left" v-if="$page.props.jetstream.hasTeamFeatures"
                                    class="md:hidden pb-3 h-full">
                                    <template #trigger>
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </template>
                                    <ListGroup>
                                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                                            <ListGroupItem>
                                                <template #prefix>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                        </path>
                                                    </svg>
                                                </template>

                                                <Link :href="route('teams.show', $page.props.user.current_team)">
                                                Team Settings
                                                </Link>
                                            </ListGroupItem>
                                            <ListGroupItem v-if="$page.props.jetstream.canCreateTeams">
                                                <template #prefix>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                </template>

                                                <Link :href="route('teams.create')">
                                                Create New Team
                                                </Link>
                                            </ListGroupItem>

                                            <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                                <ListGroupItem>
                                                    <template #prefix>
                                                        <svg v-if="team.id == $page.props.user.current_team_id"
                                                            class="h-4 w-4 text-green-400" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>

                                                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </template>
                                                    <form @submit.prevent="switchToTeam(team)" class="w-full">
                                                        <button class="w-full text-left">{{ team.name }}</button>
                                                    </form>

                                                </ListGroupItem>
                                            </template>
                                        </template>

                                    </ListGroup>
                                </Dropdown>
                            </div>
                        </div>


                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </NavLink>
                        <NavLink :href="route('dashboard')">Services</NavLink>
                        <NavLink :href="route('dashboard')">Pricing</NavLink>
                        <NavbarLink :link="route('dashboard')">Contact</NavbarLink>
                    </NavbarCollapse>
                </template>
                <template #right-side>
                    <Dropdown placement="left" v-if="$page.props.jetstream.hasTeamFeatures">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                    {{ $page.props.user.current_team.name }}

                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <ListGroup>
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <ListGroupItem>
                                    <template #prefix>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                            </path>
                                        </svg>
                                    </template>

                                    <Link :href="route('teams.show', $page.props.user.current_team)">
                                    Team Settings
                                    </Link>
                                </ListGroupItem>
                                <ListGroupItem v-if="$page.props.jetstream.canCreateTeams">
                                    <template #prefix>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </template>

                                    <Link :href="route('teams.create')">
                                    Create New Team
                                    </Link>
                                </ListGroupItem>

                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ListGroupItem>
                                            <template #prefix>
                                                <svg v-if="team.id == $page.props.user.current_team_id"
                                                    class="mr-2 h-4 w-4 text-green-400" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>

                                                <svg v-else class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </template>

                                            <button>{{ team.name }}</button>
                                        </ListGroupItem>
                                    </form>
                                </template>
                            </template>
                        </ListGroup>
                    </Dropdown>
                    <Dropdown placement="left">
                        <template #trigger>
                            <button v-if="$page.props.jetstream.managesProfilePhotos">
                                <Avatar status="online" bordered rounded :img="$page.props.user.profile_photo_url"
                                    class="mr-2.5" />
                            </button>

                            <span v-else class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                    {{ $page.props.user.name }}

                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <ListGroup>
                            <ListGroupItem>
                                <template #prefix>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </template>
                                <Link :href="route('profile.show')" class="w-full">
                                Profile
                                </Link>
                            </ListGroupItem>
                            <ListGroupItem v-if="$page.props.jetstream.hasApiFeatures">
                                <template #prefix>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                        </path>
                                    </svg>
                                </template>

                                <Link :href="route('api-tokens.index')" class="w-full">
                                API Tokens
                                </Link>
                            </ListGroupItem>
                            <ListGroupItem>
                                <template #prefix>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </template>
                                <form @submit.prevent="logout" class="w-full">
                                    <button class="w-full text-left">
                                        Log Out
                                    </button>
                                </form>
                            </ListGroupItem>
                        </ListGroup>
                    </Dropdown>
                </template>
            </Navbar>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
