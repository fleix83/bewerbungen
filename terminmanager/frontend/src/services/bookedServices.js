import { servicesAPI } from './api'

// Service ID 3 stands for the dropdown choice; it is shown under the name the
// customer picked. For "Etwas anderes" the (truncated) notes are shown instead.
function dropdownServiceName(serviceType, notes) {
  let name = serviceType || 'Etwas anderes'
  if (name === 'Etwas anderes' && notes) {
    name = notes.length > 50 ? notes.substring(0, 50) + '...' : notes
  }
  return name
}

// Returns the booked services as [{ id, name, price }] for the overview and
// confirmation screens.
export async function loadBookedServices(serviceIds, serviceType, notes) {
  const dropdownName = dropdownServiceName(serviceType, notes)

  let allServices
  try {
    const response = await servicesAPI.getAll()
    // Service ID 3 from the API is replaced by the dropdown selection
    allServices = response.data.filter(s => s.id !== 3)
  } catch (error) {
    console.error('Failed to load services:', error)
    allServices = [
      { id: 1, name: 'Lebenslauf', price: 30 },
      { id: 2, name: 'Bewerbungsschreiben', price: 30 }
    ]
  }

  if (serviceIds.includes(3)) {
    allServices.push({ id: 3, name: dropdownName, price: 30 })
  }

  return allServices.filter(service => serviceIds.includes(service.id))
}

// Service IDs for a booking: the checkboxes plus ID 3 when the dropdown has a
// priced option (not "Etwas anderes").
export function bookingServiceIds(selectedServices, serviceType) {
  const serviceIds = [...selectedServices]
  if (serviceType && serviceType !== 'Etwas anderes' && !serviceIds.includes(3)) {
    serviceIds.push(3)
  }
  return serviceIds
}
