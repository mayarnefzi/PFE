#!/usr/bin/env python
# coding: utf-8

# In[5]:


pip install Flask


# In[6]:


pip install plotly


# In[ ]:


from flask import Flask, render_template, jsonify
import pandas as pd
import plotly.express as px

app = Flask(__name__)

# Fonction pour lire le fichier Excel et générer le graphique Plotly
def generate_plot():
    file_path = 'C:\\Users\\nefzi\\Downloads\\SITE_TELC_nv_signal.xlsx'
    df = pd.read_excel(file_path)

    df = df.dropna()
    df['id_site'] = df['id_site'].astype(int)
    df['cellule'] = df['cellule'].astype(str)

    sites_par_gouvernorat = df.groupby('Gouvernorat').agg(
        Nombre_de_Sites=('site', 'count'),
        Noms_des_Sites=('site', lambda x: ', '.join(x))
    ).reset_index()

    fig_gouvernorat = px.bar(
        sites_par_gouvernorat, 
        x='Gouvernorat', 
        y='Nombre_de_Sites', 
        title='Nombre de Sites par Gouvernorat', 
        hover_data={'Noms_des_Sites': True}
    )

    fig_gouvernorat.update_layout(hovermode='x unified')

    plot_html = fig_gouvernorat.to_html(full_html=False)

    return plot_html

# Route pour afficher le graphique
@app.route('/')
def index():
    return render_template('index.html', plot=generate_plot())

# Route pour fournir les données de tableau de bord via une API
@app.route('/dashboard-data')
def get_dashboard_data():
    file_path = 'C:\\Users\\nefzi\\Downloads\\SITE_TELC_nv_signal.xlsx'
    df = pd.read_excel(file_path)
    
    df = df.dropna()
    df['id_site'] = df['id_site'].astype(int)
    df['cellule'] = df['cellule'].astype(str)
    
    sites_par_gouvernorat = df.groupby('Gouvernorat').agg(
        Nombre_de_Sites=('site', 'count'),
        Noms_des_Sites=('site', lambda x: ', '.join(x))
    ).reset_index()
    
    dashboard_data = sites_par_gouvernorat.to_dict(orient='records')
    
    return jsonify(dashboard_data)

if __name__ == '__main__':
    app.run(host='localhost', port=5000)


# In[ ]:




