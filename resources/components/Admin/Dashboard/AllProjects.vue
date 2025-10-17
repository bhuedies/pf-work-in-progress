<template>
    <v-card
        class="all-projects-card trezo-card border-radius d-block bg-white border-0 shadow-none"
    >
        <div class="v-card-header">
            <h5 class="mb-0">All Projects</h5>
            <div class="d-flex align-items-center">
                <v-menu>
                    <template v-slot:activator="{ props }">
                        <button
                            type="button"
                            v-bind="props"
                            class="card-header-menu border-none d-inline-block border-radius"
                        >
                            This Week
                        </button>
                    </template>
                    <v-list class="menu-content">
                        <button
                            type="button"
                            class="bg-transparent border-none"
                        >
                            This Day
                        </button>
                        <button
                            type="button"
                            class="bg-transparent border-none"
                        >
                            This Week
                        </button>
                        <button
                            type="button"
                            class="bg-transparent border-none"
                        >
                            This Month
                        </button>
                        <button
                            type="button"
                            class="bg-transparent border-none"
                        >
                            This Year
                        </button>
                    </v-list>
                </v-menu>
            </div>
        </div>
        <div class="trezo-table-content">
            <div class="trezo-table">
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-start">ID</th>
                            <th class="text-start">Project Name</th>
                            <th class="text-start">Client</th>
                            <th class="text-start">Assignees</th>
                            <th class="text-start">Budget</th>
                            <th class="text-start">Start Date</th>
                            <th class="text-start">End Date</th>
                            <th class="text-start">Status</th>
                            <th class="text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.slice(0, 5)" :key="item.id">
                            <td class="text-body">{{ item.id }}</td>
                            <td class="text-start">
                                <RouterLink
                                    to="/dashboard"
                                    class="project-name fw-medium"
                                >
                                    {{ item.projectName }}
                                </RouterLink>
                            </td>
                            <td>{{ item.client }}</td>
                            <td>
                                <div
                                    class="assignees d-flex align-items-center"
                                >
                                    <div
                                        v-for="userImg in item.assignees.images"
                                        :key="userImg.id"
                                    >
                                        <v-img
                                            :src="userImg.image"
                                            class="rounded-circle"
                                            alt="user-image"
                                        />
                                    </div>
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-medium z-index"
                                    >
                                        {{ item.assignees.number }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-body">${{ item.budget }}</td>
                            <td class="text-body">{{ item.startDate }}</td>
                            <td class="text-body">{{ item.endDate }}</td>
                            <td>
                                <span
                                    class="trezo-status-badge"
                                    :class="computeClass(item.status)"
                                >
                                    {{ item.status }}
                                </span>
                            </td>
                            <td>
                                <div
                                    class="action-buttons d-flex align-items-center"
                                >
                                    <button type="button">
                                        <i class="material-symbols-outlined">
                                            {{ item.action.view }}
                                        </i>
                                    </button>
                                    <button type="button">
                                        <i class="material-symbols-outlined">
                                            {{ item.action.edit }}
                                        </i>
                                    </button>
                                    <button type="button">
                                        <i class="material-symbols-outlined">
                                            {{ item.action.delete }}
                                        </i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </div>
            <PaginationTwo items="5" />
        </div>
    </v-card>
</template>

<script>
import { defineComponent, ref } from "vue";
import PaginationTwo from "../../Common/PaginationTwo.vue";

import user15 from "../../../../public/images/users/user15.jpg";
import user6 from "../../../../public/images/users/user6.jpg";
import user7 from "../../../../public/images/users/user7.jpg";
import user8 from "../../../../public/images/users/user8.jpg";
import user9 from "../../../../public/images/users/user9.jpg";
import user10 from "../../../../public/images/users/user10.jpg";
import user11 from "../../../../public/images/users/user11.jpg";
import user12 from "../../../../public/images/users/user12.jpg";
import user13 from "../../../../public/images/users/user13.jpg";
import user14 from "../../../../public/images/users/user14.jpg";

export default defineComponent({
    name: "AllProjects",
    components: {
        PaginationTwo,
    },
    setup() {
        const items = ref([
            {
                id: "#854",
                projectName: "Project CyberSphere",
                client: "NovaTech Solutions",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user15,
                        },
                        {
                            id: 2,
                            image: user11,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                        {
                            id: 4,
                            image: user9,
                        },
                    ],
                    number: "+10",
                },
                budget: "4,500",
                startDate: "25 Mar 2024",
                endDate: "25 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#853",
                projectName: "Digital Oasis Initiative",
                client: "AlphaWave Innovations",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user8,
                        },
                        {
                            id: 3,
                            image: user9,
                        },
                    ],
                    number: "+04",
                },
                budget: "6,800",
                startDate: "20 Mar 2024",
                endDate: "20 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#852",
                projectName: "CloudScape Evolution",
                client: "InnovateIQ Inc.",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user10,
                        },
                        {
                            id: 2,
                            image: user12,
                        },
                    ],
                    number: "+07",
                },
                budget: "2,500",
                startDate: "15 Mar 2024",
                endDate: "15 Apr 2024",
                status: "Pending",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#851",
                projectName: "Data Dynamo Drive",
                client: "BlueSky Technologies",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user13,
                        },
                        {
                            id: 2,
                            image: user14,
                        },
                        {
                            id: 3,
                            image: user15,
                        },
                        {
                            id: 4,
                            image: user12,
                        },
                    ],
                    number: "+15",
                },
                budget: "7,500",
                startDate: "10 Mar 2024",
                endDate: "10 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#849",
                projectName: "QuantumLeap Quest",
                client: "NexGen Systems",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user9,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                    ],
                    number: "+03",
                },
                budget: "3,400",
                startDate: "05 Mar 2024",
                endDate: "05 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#849",
                projectName: "QuantumLeap Quest",
                client: "NexGen Systems",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user9,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                    ],
                    number: "+03",
                },
                budget: "3,400",
                startDate: "05 Mar 2024",
                endDate: "05 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#851",
                projectName: "Data Dynamo Drive",
                client: "BlueSky Technologies",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user13,
                        },
                        {
                            id: 2,
                            image: user14,
                        },
                        {
                            id: 3,
                            image: user15,
                        },
                        {
                            id: 4,
                            image: user12,
                        },
                    ],
                    number: "+15",
                },
                budget: "7,500",
                startDate: "10 Mar 2024",
                endDate: "10 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#853",
                projectName: "Digital Oasis Initiative",
                client: "AlphaWave Innovations",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user8,
                        },
                        {
                            id: 3,
                            image: user9,
                        },
                    ],
                    number: "+04",
                },
                budget: "6,800",
                startDate: "20 Mar 2024",
                endDate: "20 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#854",
                projectName: "Project CyberSphere",
                client: "NovaTech Solutions",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user15,
                        },
                        {
                            id: 2,
                            image: user11,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                        {
                            id: 4,
                            image: user9,
                        },
                    ],
                    number: "+10",
                },
                budget: "4,500",
                startDate: "25 Mar 2024",
                endDate: "25 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#852",
                projectName: "CloudScape Evolution",
                client: "InnovateIQ Inc.",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user10,
                        },
                        {
                            id: 2,
                            image: user12,
                        },
                    ],
                    number: "+07",
                },
                budget: "2,500",
                startDate: "15 Mar 2024",
                endDate: "15 Apr 2024",
                status: "Pending",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#854",
                projectName: "Project CyberSphere",
                client: "NovaTech Solutions",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user15,
                        },
                        {
                            id: 2,
                            image: user11,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                        {
                            id: 4,
                            image: user9,
                        },
                    ],
                    number: "+10",
                },
                budget: "4,500",
                startDate: "25 Mar 2024",
                endDate: "25 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#853",
                projectName: "Digital Oasis Initiative",
                client: "AlphaWave Innovations",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user8,
                        },
                        {
                            id: 3,
                            image: user9,
                        },
                    ],
                    number: "+04",
                },
                budget: "6,800",
                startDate: "20 Mar 2024",
                endDate: "20 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#852",
                projectName: "CloudScape Evolution",
                client: "InnovateIQ Inc.",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user10,
                        },
                        {
                            id: 2,
                            image: user12,
                        },
                    ],
                    number: "+07",
                },
                budget: "2,500",
                startDate: "15 Mar 2024",
                endDate: "15 Apr 2024",
                status: "Pending",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#851",
                projectName: "Data Dynamo Drive",
                client: "BlueSky Technologies",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user13,
                        },
                        {
                            id: 2,
                            image: user14,
                        },
                        {
                            id: 3,
                            image: user15,
                        },
                        {
                            id: 4,
                            image: user12,
                        },
                    ],
                    number: "+15",
                },
                budget: "7,500",
                startDate: "10 Mar 2024",
                endDate: "10 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#849",
                projectName: "QuantumLeap Quest",
                client: "NexGen Systems",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user9,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                    ],
                    number: "+03",
                },
                budget: "3,400",
                startDate: "05 Mar 2024",
                endDate: "05 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#849",
                projectName: "QuantumLeap Quest",
                client: "NexGen Systems",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user9,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                    ],
                    number: "+03",
                },
                budget: "3,400",
                startDate: "05 Mar 2024",
                endDate: "05 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#851",
                projectName: "Data Dynamo Drive",
                client: "BlueSky Technologies",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user13,
                        },
                        {
                            id: 2,
                            image: user14,
                        },
                        {
                            id: 3,
                            image: user15,
                        },
                        {
                            id: 4,
                            image: user12,
                        },
                    ],
                    number: "+15",
                },
                budget: "7,500",
                startDate: "10 Mar 2024",
                endDate: "10 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#853",
                projectName: "Digital Oasis Initiative",
                client: "AlphaWave Innovations",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user7,
                        },
                        {
                            id: 2,
                            image: user8,
                        },
                        {
                            id: 3,
                            image: user9,
                        },
                    ],
                    number: "+04",
                },
                budget: "6,800",
                startDate: "20 Mar 2024",
                endDate: "20 Apr 2024",
                status: "In Progress",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#854",
                projectName: "Project CyberSphere",
                client: "NovaTech Solutions",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user15,
                        },
                        {
                            id: 2,
                            image: user11,
                        },
                        {
                            id: 3,
                            image: user6,
                        },
                        {
                            id: 4,
                            image: user9,
                        },
                    ],
                    number: "+10",
                },
                budget: "4,500",
                startDate: "25 Mar 2024",
                endDate: "25 Apr 2024",
                status: "Finished",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
            {
                id: "#852",
                projectName: "CloudScape Evolution",
                client: "InnovateIQ Inc.",
                assignees: {
                    className: "ms-m-15",
                    images: [
                        {
                            id: 1,
                            image: user10,
                        },
                        {
                            id: 2,
                            image: user12,
                        },
                    ],
                    number: "+07",
                },
                budget: "2,500",
                startDate: "15 Mar 2024",
                endDate: "15 Apr 2024",
                status: "Pending",
                action: {
                    view: "visibility",
                    edit: "edit",
                    delete: "delete",
                },
            },
        ]);

        const wordConfirmed = ref("Finished");
        const wordInProgress = ref("In Progress");
        const wordPending = ref("Pending");

        const computeClass = (classValue) => {
            return {
                pFinished: wordConfirmed.value === classValue,
                pInProgress: wordInProgress.value === classValue,
                pPending: wordPending.value === classValue,
            };
        };

        return {
            items,
            computeClass,
        };
    },
});
</script>

<style lang="scss" scoped>
.all-projects-card {
    .trezo-table-content {
        .trezo-table {
            margin-left: -25px;
            margin-right: -25px;
            table {
                thead {
                    tr {
                        th {
                            background-color: #ecf0ff !important;

                            &:first-child {
                                border-top-left-radius: 0 !important;
                                padding-left: 25px;
                            }
                            &:last-child {
                                border-top-right-radius: 0 !important;
                                padding-right: 25px;
                            }
                        }
                    }
                }
                tbody {
                    tr {
                        td {
                            &:first-child {
                                padding-left: 25px;
                                border-left-width: 0 !important;
                            }
                            &:last-child {
                                padding-right: 25px;
                                border-right-width: 0 !important;
                            }
                        }
                        &:first-child {
                            td {
                                border-top-width: 0 !important;
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>
