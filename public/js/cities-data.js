// Global cities data untuk seluruh aplikasi
window.citiesData = [
    'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Bekasi', 'Depok', 'Tangerang', 'Semarang', 'Palembang', 'Makassar',
    'Bogor', 'Batam', 'Padang', 'Pekanbaru', 'Denpasar', 'Malang', 'Samarinda', 'Tasikmalaya', 'Pontianak', 'Banjarmasin',
    'Balikpapan', 'Jambi', 'Cimahi', 'Yogyakarta', 'Kediri', 'Cilegon', 'Cirebon', 'Mataram', 'Manado', 'Kupang',
    'Ambon', 'Jayapura', 'Palu', 'Kendari', 'Ternate', 'Sorong', 'Banda Aceh', 'Pangkal Pinang', 'Bengkulu', 'Gorontalo',
    'Tarakan', 'Bitung', 'Tanjung Pinang', 'Lubuklinggau', 'Pematangsiantar', 'Banjarbaru', 'Probolinggo', 'Magelang', 'Blitar', 'Mojokerto'
];

// Helper function untuk setup location select
window.setupLocationSelect = function(selectElement, selectedValue = '') {
    if (!selectElement) return;
    
    selectElement.empty();
    selectElement.append('<option value="">Pilih Kota...</option>');
    
    window.citiesData.forEach(function(city) {
        const isSelected = city === selectedValue ? 'selected' : '';
        selectElement.append(`<option value="${city}" ${isSelected} data-icon="city">${city}</option>`);
    });
    
    // Setup Select2 jika tersedia
    if (typeof $.fn.select2 !== 'undefined') {
        selectElement.select2({
            placeholder: 'Pilih Kota...',
            allowClear: true,
            width: '100%',
            templateResult: function (data) {
                if (!data.id) return data.text;
                return $('<span> '+data.text+'</span>');
            },
            templateSelection: function (data) {
                if (!data.id) return data.text;
                return $('<span> '+data.text+'</span>');
            }
        });
    }
};
