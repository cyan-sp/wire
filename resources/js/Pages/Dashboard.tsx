import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

interface Plan {
    id: number;
    name: string;
    code: string;
    prefix: string;
    numbering?: string; // Optional because it's only available in 'My Plans'
}

export default function Dashboard() {
    const [availablePlans, setAvailablePlans] = useState<Plan[]>([]);
    const [myPlans, setMyPlans] = useState<Plan[]>([]);

    // Fetch plans on component mount
    useEffect(() => {
        fetchAvailablePlans();
        fetchMyPlans();
    }, []);

    // Fetch available plans
    const fetchAvailablePlans = async () => {
        try {
            const response = await fetch('/api/available-plans');
            if (response.ok) {
                const data = await response.json();
                setAvailablePlans(data);
            } else {
                console.error('Failed to fetch available plans.');
            }
        } catch (error) {
            console.error('Error fetching available plans:', error);
        }
    };

    // Fetch user's plans
    const fetchMyPlans = async () => {
        try {
            const response = await fetch('/api/my-plans');
            if (response.ok) {
                const data = await response.json();
                setMyPlans(data);
            } else {
                console.error('Failed to fetch my plans.');
            }
        } catch (error) {
            console.error('Error fetching my plans:', error);
        }
    };

    // Associate a plan
    const associatePlan = async (planId: number) => {
        try {
            const response = await fetch('/api/associate-plan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ planId }),
            });

            if (response.ok) {
                alert('Plan associated successfully!');
                fetchAvailablePlans();
                fetchMyPlans();
            } else {
                const errorData = await response.json();
                alert(
                    'Failed to associate plan: ' +
                        (errorData.message || 'Unknown error'),
                );
            }
        } catch (error) {
            console.error('Error associating plan:', error);
            alert('An unexpected error occurred.');
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* Available Plans Section */}
                    <div className="rounded-lg bg-white p-6 shadow-md">
                        <h3 className="mb-4 text-lg font-bold">
                            Available Plans
                        </h3>
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {availablePlans.length > 0 ? (
                                availablePlans.map((plan) => (
                                    <div
                                        key={plan.id}
                                        className="card bg-base-100 shadow-md"
                                    >
                                        <div className="card-body">
                                            <h4 className="card-title">
                                                {plan.name}
                                            </h4>
                                            <p>Code: {plan.code}</p>
                                            <p>Prefix: {plan.prefix}</p>
                                            <button
                                                className="btn btn-primary mt-4"
                                                onClick={() =>
                                                    associatePlan(plan.id)
                                                }
                                            >
                                                Associate
                                            </button>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p>No available plans.</p>
                            )}
                        </div>
                    </div>

                    {/* My Plans Section */}
                    <div className="mt-8 rounded-lg bg-white p-6 shadow-md">
                        <h3 className="mb-4 text-lg font-bold">My Plans</h3>
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {myPlans.length > 0 ? (
                                myPlans.map((plan) => (
                                    <div
                                        key={plan.id}
                                        className="card bg-base-100 shadow-md"
                                    >
                                        <div className="card-body">
                                            <h4 className="card-title">
                                                {plan.name}
                                            </h4>
                                            <p>Code: {plan.code}</p>
                                            <p>Prefix: {plan.prefix}</p>
                                            <p>Numbering: {plan.numbering}</p>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p>You have no plans associated.</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
