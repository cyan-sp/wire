import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

interface Plan {
    id: number;
    name: string;
    code: string;
    prefix: string;
    numbering?: string;
}

interface Brand {
    id: number;
    name: string;
}

export default function Dashboard() {
    const [brands, setBrands] = useState<Brand[]>([]);
    const [selectedBrand, setSelectedBrand] = useState<number | null>(null);
    const [availablePlans, setAvailablePlans] = useState<Plan[]>([]);
    const [myPlans, setMyPlans] = useState<Plan[]>([]);

    // Fetch brands on component mount
    useEffect(() => {
        fetchBrands();
        fetchMyPlans();
    }, []);

    // Fetch brands
    const fetchBrands = async () => {
        try {
            const response = await fetch('/api/brands');
            if (response.ok) {
                const data = await response.json();
                setBrands(data);
            } else {
                console.error('Failed to fetch brands.');
            }
        } catch (error) {
            console.error('Error fetching brands:', error);
        }
    };

    // Fetch brand plans
    const fetchBrandPlans = async (brandId: number) => {
        try {
            const response = await fetch(`/api/brands/${brandId}/plans`);
            if (response.ok) {
                const data = await response.json();
                setAvailablePlans(data);
                setSelectedBrand(brandId);
            } else {
                console.error('Failed to fetch brand plans.');
            }
        } catch (error) {
            console.error('Error fetching brand plans:', error);
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
                if (selectedBrand) {
                    fetchBrandPlans(selectedBrand);
                }
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
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {/* Brands Section */}
                    <div className="p-6">
                        <h3 className="mb-4 text-lg font-bold">Brands</h3>
                        <div className="flex flex-wrap gap-4">
                            {brands.map((brand) => (
                                <button
                                    key={brand.id}
                                    onClick={() => fetchBrandPlans(brand.id)}
                                    className={`rounded-lg px-4 py-2 ${
                                        selectedBrand === brand.id
                                            ? 'bg-primary text-white'
                                            : 'bg-base-100 hover:bg-base-200'
                                    }`}
                                >
                                    {brand.name}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Available Plans Section */}
                    {selectedBrand && (
                        <div className="p-6">
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
                                    <p>No available plans for this brand.</p>
                                )}
                            </div>
                        </div>
                    )}

                    {/* My Plans Section */}
                    <div className="mt-8 p-6">
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
