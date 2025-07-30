@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Statistiques et Analyses Pédagogiques</h1>
                <p class="text-sm text-gray-600 mt-1">Tableau de bord coordinateur - Analyse des données de présence et cours</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="exportCharts()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Exporter
                </button>
                <button onclick="refreshData()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Actualiser
                </button>
                <a href="{{ route('coordinateur.dashboard') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour Dashboard
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="bg-gray-50 min-h-screen">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Message d'information pour le coordinateur -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Espace Coordinateur Pédagogique</strong> - Utilisez ces statistiques pour identifier les étudiants en difficulté et optimiser l'organisation des cours.
                    </p>
                </div>
            </div>
        </div>

        <!-- Graphique 1: Taux de présence par étudiant -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Taux de Présence par Étudiant</h2>
                    <p class="text-sm text-gray-600">Classement par ordre décroissant avec code couleur pour identifier les cas prioritaires</p>
                </div>
                <div class="flex space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-600 rounded mr-2"></div>
                        <span>≥ 70% (Excellent)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
                        <span>50.1-69.9% (Bien)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span>30.1-50% (À surveiller)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-600 rounded mr-2"></div>
                        <span>≤ 30% (Intervention nécessaire)</span>
                    </div>
                </div>
            </div>
            <div class="h-96">
                <canvas id="studentsChart"></canvas>
            </div>
        </div>

        <!-- Graphique 2: Taux de présence par classe -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Performance par Classe</h2>
                    <p class="text-sm text-gray-600">Comparaison des taux de présence pour identifier les classes nécessitant un accompagnement</p>
                </div>
                <select id="classViewType" class="border border-gray-300 rounded-lg px-3 py-2">
                    <option value="bar">Graphique en barres</option>
                    <option value="pie">Graphique circulaire</option>
                </select>
            </div>
            <div class="h-96">
                <canvas id="classesChart"></canvas>
            </div>
        </div>

        <!-- Graphique 3: Volume de cours dispensés -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Répartition des Types de Cours</h2>
                    <p class="text-sm text-gray-600">Analyse du volume et de la répartition des différentes modalités pédagogiques</p>
                </div>
                <div class="flex space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-600 rounded mr-2"></div>
                        <span>Présentiel</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-600 rounded mr-2"></div>
                        <span>E-learning</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                        <span>Workshop</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="h-80">
                    <canvas id="coursesChart"></canvas>
                </div>
                <div class="h-80">
                    <canvas id="coursesBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Actions coordinateur -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Actions Recommandées</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="text-2xl font-bold text-red-600" id="criticalStudents">
                        {{ collect($studentsStats)->where('taux_presence', '<=', 30)->count() }}
                    </div>
                    <div class="text-sm text-red-700 font-medium">Étudiants en situation critique</div>
                    <div class="text-xs text-red-600 mt-1">Intervention immédiate requise</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="text-2xl font-bold text-yellow-600" id="warningStudents">
                        {{ collect($studentsStats)->whereBetween('taux_presence', [30.1, 50])->count() }}
                    </div>
                    <div class="text-sm text-yellow-700 font-medium">Étudiants à surveiller</div>
                    <div class="text-xs text-yellow-600 mt-1">Suivi renforcé conseillé</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600" id="goodStudents">
                        {{ collect($studentsStats)->where('taux_presence', '>=', 70)->count() }}
                    </div>
                    <div class="text-sm text-green-700 font-medium">Étudiants assidus</div>
                    <div class="text-xs text-green-600 mt-1">Performance satisfaisante</div>
                </div>
            </div>
        </div>

        <!-- Tableau de résumé -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Résumé Statistique</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600" id="totalStudents">{{ count($studentsStats) }}</div>
                    <div class="text-sm text-gray-600">Étudiants analysés</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600" id="avgPresence">
                        {{ count($studentsStats) > 0 ? number_format(collect($studentsStats)->avg('taux_presence'), 1) : 0 }}%
                    </div>
                    <div class="text-sm text-gray-600">Taux moyen de présence</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600" id="totalCourses">
                        {{ $coursesVolume['presentiel'] + $coursesVolume['e_learning'] + $coursesVolume['workshop'] }}
                    </div>
                    <div class="text-sm text-gray-600">Total cours dispensés</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600" id="totalClasses">{{ count($classesStats) }}</div>
                    <div class="text-sm text-gray-600">Classes actives</div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Données du serveur
const studentsData = @json($studentsStats);
const classesData = @json($classesStats);
const coursesData = @json($coursesVolume);

// Configuration des graphiques
Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
Chart.defaults.color = '#6b7280';

// Graphique 1: Taux de présence par étudiant
const studentsCtx = document.getElementById('studentsChart').getContext('2d');
const studentsChart = new Chart(studentsCtx, {
    type: 'bar',
    data: {
        labels: studentsData.map(student => student.nom),
        datasets: [{
            label: 'Taux de présence (%)',
            data: studentsData.map(student => student.taux_presence),
            backgroundColor: studentsData.map(student => student.color),
            borderColor: studentsData.map(student => student.color),
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    afterLabel: function(context) {
                        const student = studentsData[context.dataIndex];
                        return [
                            `Classe: ${student.classe}`,
                            `Présences: ${student.presences}/${student.total_seances}`,
                            `Action: ${getRecommendation(student.taux_presence)}`
                        ];
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            },
            x: {
                ticks: {
                    maxRotation: 45
                }
            }
        }
    }
});

// Fonction pour les recommandations
function getRecommendation(rate) {
    if (rate >= 70) return 'RAS - Bon suivi';
    if (rate >= 50.1) return 'Encouragement conseillé';
    if (rate >= 30.1) return 'Suivi renforcé requis';
    return 'URGENT - Convocation nécessaire';
}

// Graphique 2: Taux de présence par classe
const classesCtx = document.getElementById('classesChart').getContext('2d');
let classesChart = new Chart(classesCtx, {
    type: 'bar',
    data: {
        labels: classesData.map(classe => classe.nom_classe),
        datasets: [{
            label: 'Taux de présence (%)',
            data: classesData.map(classe => classe.taux_presence),
            backgroundColor: '#3b82f6',
            borderColor: '#2563eb',
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    afterLabel: function(context) {
                        const classe = classesData[context.dataIndex];
                        return [
                            `Étudiants: ${classe.total_etudiants}`,
                            `Séances: ${classe.total_seances}`,
                            `Présences effectives: ${classe.presences_reelles}`
                        ];
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Graphique 3: Volume de cours (Camembert)
const coursesCtx = document.getElementById('coursesChart').getContext('2d');
const coursesChart = new Chart(coursesCtx, {
    type: 'doughnut',
    data: {
        labels: ['Présentiel', 'E-learning', 'Workshop'],
        datasets: [{
            data: [coursesData.presentiel, coursesData.e_learning, coursesData.workshop],
            backgroundColor: ['#2563eb', '#7c3aed', '#f59e0b'],
            borderColor: ['#1d4ed8', '#6d28d9', '#d97706'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((context.parsed / total) * 100);
                        return `${context.label}: ${context.parsed} cours (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Graphique 3: Volume de cours (Barres)
const coursesBarCtx = document.getElementById('coursesBarChart').getContext('2d');
const coursesBarChart = new Chart(coursesBarCtx, {
    type: 'bar',
    data: {
        labels: ['Présentiel', 'E-learning', 'Workshop'],
        datasets: [{
            label: 'Nombre de cours',
            data: [coursesData.presentiel, coursesData.e_learning, coursesData.workshop],
            backgroundColor: ['#2563eb', '#7c3aed', '#f59e0b'],
            borderColor: ['#1d4ed8', '#6d28d9', '#d97706'],
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Fonctions utilitaires
function exportCharts() {
    alert('Fonctionnalité d\'export en cours de développement - Coordinateur');
}

function refreshData() {
    location.reload();
}

// Changement de type de graphique pour les classes
document.getElementById('classViewType').addEventListener('change', function(e) {
    const newType = e.target.value;
    classesChart.destroy();

    classesChart = new Chart(classesCtx, {
        type: newType,
        data: {
            labels: classesData.map(classe => classe.nom_classe),
            datasets: [{
                label: 'Taux de présence (%)',
                data: classesData.map(classe => classe.taux_presence),
                backgroundColor: newType === 'pie' ?
                    ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'] :
                    '#3b82f6',
                borderColor: newType === 'pie' ?
                    ['#dc2626', '#d97706', '#059669', '#2563eb', '#7c3aed', '#db2777'] :
                    '#2563eb',
                borderWidth: 1,
                borderRadius: newType === 'bar' ? 4 : 0,
                borderSkipped: newType === 'bar' ? false : undefined,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: newType === 'pie',
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const classe = classesData[context.dataIndex];
                            return [
                                `Étudiants: ${classe.total_etudiants}`,
                                `Séances: ${classe.total_seances}`,
                                `Présences: ${classe.presences_reelles}`
                            ];
                        }
                    }
                }
            },
            scales: newType === 'bar' ? {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            } : {}
        }
    });
});
</script>

<style>
.chart-container {
    position: relative;
    height: 400px;
    margin: 20px 0;
}

canvas {
    max-height: 100% !important;
}

/* Animation des barres */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 100%, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.bg-white {
    animation: fadeInUp 0.6s ease-out;
}

/* Style spécifique coordinateur */
.border-l-4 {
    border-left-width: 4px;
}
</style>
@endsection
